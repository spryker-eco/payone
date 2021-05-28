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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer): AbstractAuthorizationContainer
    {
        $authorizationContainer = new AuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $authorizationContainer);
        $authorizationContainer = $this->mapEmail($paymentEntity, $authorizationContainer);
        $authorizationContainer = $this->mapBusinessRelation($authorizationContainer);
        $authorizationContainer->setAmount($orderTransfer->getTotals()->getGrandTotal());

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity): PreAuthorizationContainerInterface
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapEmail($paymentEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapAmount($paymentEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapBusinessRelation($preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    public function mapEmail(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $container): AbstractAuthorizationContainer
    {
        $container->setEmail($paymentEntity->getSpySalesOrder()->getEmail());

        return $container;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    public function mapAmount(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $container): AbstractAuthorizationContainer
    {
        $container->setAmount($paymentEntity->getSpySalesOrder()->getOrderTotals()->get(0)->getSubTotal());

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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity): CaptureContainerInterface
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();
        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $captureContainer->setTxid($paymentEntity->getTransactionId());

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    protected function mapPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentEntity,
        AbstractAuthorizationContainer $authorizationContainer
    ): AbstractAuthorizationContainer {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_SECURITY_INVOICE);
        $authorizationContainer->setClearingsubtype(PayoneApiConstants::CLEARING_SUBTYPE_SECURITY_INVOICE);
        $authorizationContainer->setReference($paymentEntity->getReference());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainerFromPayment($paymentEntity));

        $personalContainer = new PersonalContainer();
        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentEntity);

        $authorizationContainer->setPersonalData($personalContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity): DebitContainerInterface
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($paymentEntity->getTransactionId());
        $debitContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($paymentEntity->getSpyPaymentPayoneDetail()->getAmount());

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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity): RefundContainerInterface
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($paymentEntity->getTransactionId());
        $refundContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $refundContainer->setBankcountry($paymentEntity->getSpyPaymentPayoneDetail()->getBankCountry());
        $refundContainer->setBankaccount($paymentEntity->getSpyPaymentPayoneDetail()->getBankAccount());
        $refundContainer->setBankcode($paymentEntity->getSpyPaymentPayoneDetail()->getBankCode());
        $refundContainer->setBankbranchcode($paymentEntity->getSpyPaymentPayoneDetail()->getBankBranchCode());
        $refundContainer->setBankcheckdigit($paymentEntity->getSpyPaymentPayoneDetail()->getBankCheckDigit());
        $refundContainer->setIban($paymentEntity->getSpyPaymentPayoneDetail()->getIban());
        $refundContainer->setBic($paymentEntity->getSpyPaymentPayoneDetail()->getBic());

        return $refundContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    protected function createPaymentMethodContainerFromPayment(SpyPaymentPayone $paymentEntity): ContainerInterface
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
