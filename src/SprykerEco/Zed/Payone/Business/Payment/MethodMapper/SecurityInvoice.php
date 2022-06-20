<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneAuthorizationTransfer;
use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\SecurityInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetSecurityInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface;
use SprykerEco\Zed\Payone\PayoneConfig;

class SecurityInvoice extends AbstractMapper
{
    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected $payoneConfig;

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     * @param \SprykerEco\Zed\Payone\PayoneConfig $payoneConfig
     */
    public function __construct(Store $storeConfig, PayoneConfig $payoneConfig)
    {
        parent::__construct($storeConfig);
        $this->payoneConfig = $payoneConfig;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return PayoneApiConstants::PAYMENT_METHOD_SECURITY_INVOICE;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentPayoneEntity, OrderTransfer $orderTransfer): AbstractAuthorizationContainer
    {
        $authorizationContainer = new AuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $authorizationContainer);
        $authorizationContainer = $this->mapEmail($paymentPayoneEntity, $authorizationContainer);
        $authorizationContainer = $this->mapBusinessRelation($authorizationContainer);
        $authorizationContainer->setAmount($orderTransfer->getTotals()->getGrandTotal());

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentPayoneEntity): PreAuthorizationContainerInterface
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapEmail($paymentPayoneEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapAmount($paymentPayoneEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapBusinessRelation($preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    public function mapEmail(SpyPaymentPayone $paymentPayoneEntity, AbstractAuthorizationContainer $container): AbstractAuthorizationContainer
    {
        $container->setEmail($paymentPayoneEntity->getSpySalesOrder()->getEmail());

        return $container;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    public function mapAmount(SpyPaymentPayone $paymentPayoneEntity, AbstractAuthorizationContainer $container): AbstractAuthorizationContainer
    {
        $container->setAmount($paymentPayoneEntity->getSpySalesOrder()->getOrderTotals()->get(0)->getSubTotal());

        return $container;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    public function mapBusinessRelation(AbstractAuthorizationContainer $container): AbstractAuthorizationContainer
    {
        $container->setBusinessrelation($this->payoneConfig->getBusinessRelation());

        return $container;
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
        $captureContainer->setCurrency((string)$this->getStandardParameter()->getCurrency());
        $captureContainer->setTxid($paymentPayoneEntity->getTransactionId());

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    protected function mapPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentPayoneEntity,
        AbstractAuthorizationContainer $authorizationContainer
    ): AbstractAuthorizationContainer {
        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_SECURITY_INVOICE);
        $authorizationContainer->setClearingsubtype(PayoneApiConstants::CLEARING_SUBTYPE_SECURITY_INVOICE);
        $authorizationContainer->setReference($paymentPayoneEntity->getReference());
        $authorizationContainer->setCurrency((string)$this->getStandardParameter()->getCurrency());
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
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function mapGetSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): ContainerInterface
    {
        $getInvoiceContainer = new GetSecurityInvoiceContainer();
        $getInvoiceContainer->setInvoiceTitle($getSecurityInvoiceTransfer->getReference());

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
        $refundContainer->setCurrency((string)$this->getStandardParameter()->getCurrency());
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    protected function createPaymentMethodContainerFromPayment(SpyPaymentPayone $paymentPayoneEntity): ContainerInterface
    {
        $paymentMethodContainer = new SecurityInvoiceContainer();

        return $paymentMethodContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneAuthorizationTransfer $payoneAuthorizationTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    protected function createAuthorizationPersonalData(PayoneAuthorizationTransfer $payoneAuthorizationTransfer): ContainerInterface
    {
        $personalContainer = new PersonalContainer();

        $personalContainer->setFirstName($payoneAuthorizationTransfer->getOrder()->getFirstName());
        $personalContainer->setLastName($payoneAuthorizationTransfer->getOrder()->getLastName());
        $personalContainer->setCountry($this->storeConfig->getCurrentCountry());

        return $personalContainer;
    }
}
