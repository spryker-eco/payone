<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneAuthorizationTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\InvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface;

class Invoice extends AbstractMapper implements InvoiceInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return PayoneApiConstants::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentPayoneEntity, OrderTransfer $orderTransfer): AuthorizationContainerInterface
    {
        $authorizationContainer = new AuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItem
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer
     */
    public function mapOrderItemToItemContainer(ItemTransfer $orderItem): ItemContainer
    {
        $itemContainer = new ItemContainer();
        $itemContainer->setIt(PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS);
        $itemContainer->setId((string)$orderItem->getSku());
        $itemContainer->setPr($orderItem->getUnitGrossPrice());
        $itemContainer->setNo($orderItem->getQuantity());
        $itemContainer->setDe($orderItem->getName());
        $itemContainer->setVa($orderItem->getTaxRate());

        return $itemContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expense
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer
     */
    public function mapExpenseToItemContainer(ExpenseTransfer $expense): ItemContainer
    {
        $itemContainer = new ItemContainer();
        $itemContainer->setIt(PayoneApiConstants::INVOICING_ITEM_TYPE_SHIPMENT);
        $itemContainer->setId('-');
        $itemContainer->setPr($expense->getUnitGrossPrice());
        $itemContainer->setNo($expense->getQuantity());
        $itemContainer->setDe($expense->getName());
        $itemContainer->setVa($expense->getTaxRate());

        return $itemContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentPayoneEntity): CaptureContainerInterface
    {
        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();
        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        if ($this->getStandardParameter()->getCurrency() !== null) {
            $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        }
        $captureContainer->setTxid($paymentPayoneEntity->getTransactionId());

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface|\SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentPayoneEntity)
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface|\SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    protected function mapPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentPayoneEntity,
        AbstractAuthorizationContainer $authorizationContainer
    ) {
        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_INVOICE);
        $authorizationContainer->setReference($paymentPayoneEntity->getReference());
        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        if ($this->getStandardParameter()->getCurrency() !== null) {
            $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        }
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainerFromPayment($paymentPayoneEntity));

        $personalContainer = new PersonalContainer();
        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentPayoneEntity);

        $authorizationContainer->setPersonalData($personalContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentPayoneEntity): DebitContainerInterface
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($paymentPayoneEntity->getTransactionId());
        if ($paymentPayoneEntity->getTransactionId() !== null) {
            $debitContainer->setSequenceNumber($this->getNextSequenceNumber($paymentPayoneEntity->getTransactionId()));
        }
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getAmount());

        return $debitContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GetInvoiceContainer
     */
    public function mapGetInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer): GetInvoiceContainer
    {
        $getInvoiceContainer = new GetInvoiceContainer();
        $getInvoiceContainer->setInvoiceTitle((string)$getInvoiceTransfer->getReference());

        return $getInvoiceContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentPayoneEntity): RefundContainerInterface
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($paymentPayoneEntity->getTransactionId());
        if ($paymentPayoneEntity->getTransactionId() !== null) {
            $refundContainer->setSequenceNumber($this->getNextSequenceNumber($paymentPayoneEntity->getTransactionId()));
        }
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());

        $refundContainer->setBankcountry($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankCountry());
        $refundContainer->setBankaccount($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankAccount());
        $refundContainer->setBankcode($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankCode());
        $refundContainer->setBankbranchcode($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankBranchCode());
        $refundContainer->setBankcheckdigit($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankCheckDigit());
        $refundContainer->setIban($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getIban());
        $refundContainer->setBic($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBic());

        return $refundContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\InvoiceContainer
     */
    protected function createPaymentMethodContainerFromPayment(
        SpyPaymentPayone $paymentPayoneEntity
    ): InvoiceContainer {
        $paymentMethodContainer = new InvoiceContainer();

        return $paymentMethodContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneAuthorizationTransfer $payoneAuthorizationTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function createAuthorizationPersonalData(
        PayoneAuthorizationTransfer $payoneAuthorizationTransfer
    ): PersonalContainer {
        $personalContainer = new PersonalContainer();

        $personalContainer->setFirstName($payoneAuthorizationTransfer->getOrder()->getFirstName());
        $personalContainer->setLastName($payoneAuthorizationTransfer->getOrder()->getLastName());
        $personalContainer->setCountry($this->storeConfig->getCurrentCountry());

        return $personalContainer;
    }
}
