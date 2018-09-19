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
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\SecurityInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetSecurityInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\PayoneConfig;

class SecurityInvoice extends AbstractMapper
{
    /**
     * @var PayoneConfig
     */
    protected $payoneConfig;
    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     */
    public function __construct(Store $storeConfig, PayoneConfig $payoneConfig)
    {
        parent::__construct($storeConfig);
        $this->payoneConfig = $payoneConfig;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_SECURITY_INVOICE;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer)
    {
        $authorizationContainer = new AuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $authorizationContainer);
        $authorizationContainer = $this->mapOrderItems($orderTransfer, $authorizationContainer);
        $authorizationContainer = $this->mapEmail($paymentEntity, $authorizationContainer);
        $authorizationContainer = $this->mapBusinessRelation($authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity)
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapEmail($paymentEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapAmount($paymentEntity, $preAuthorizationContainer);
        $preAuthorizationContainer = $this->mapBusinessRelation($preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param AbstractAuthorizationContainer $authorizationContainer
     *
     * @return AbstractRequestContainer
     */
    public function mapOrderItems(OrderTransfer $orderTransfer, AbstractRequestContainer $authorizationContainer): AbstractRequestContainer
    {
        $arrayIt = [];
        $arrayId = [];
        $arrayPr = [];
        $arrayNo = [];
        $arrayDe = [];
        $arrayVa = [];

        $key = 1;

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS;
            $arrayId[$key] = $itemTransfer->getSku();
            $arrayPr[$key] = $itemTransfer->getSumPrice();
            $arrayNo[$key] = $itemTransfer->getQuantity();
            $arrayDe[$key] = $itemTransfer->getName();
            $arrayVa[$key] = (int) $itemTransfer->getTaxRate();
            $key++;
        }

        $authorizationContainer->setIt($arrayIt);
        $authorizationContainer->setId($arrayId);
        $authorizationContainer->setPr($arrayPr);
        $authorizationContainer->setNo($arrayNo);
        $authorizationContainer->setDe($arrayDe);
        $authorizationContainer->setVa($arrayVa);
        $authorizationContainer->setAmount($orderTransfer->getTotals()->getSubtotal());

        return $authorizationContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AbstractAuthorizationContainer $container
     * @return AbstractAuthorizationContainer
     */
    public function mapEmail(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $container): AbstractAuthorizationContainer
    {
        $container->setEmail($paymentEntity->getSpySalesOrder()->getEmail());

        return $container;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AbstractAuthorizationContainer $container
     * @return AbstractAuthorizationContainer
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function mapAmount(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $container): AbstractAuthorizationContainer
    {
        $container->setAmount($paymentEntity->getSpySalesOrder()->getOrderTotals()->get(0)->getSubTotal());

        return $container;
    }

    /**
     * @param AbstractAuthorizationContainer $container
     * @return AbstractAuthorizationContainer
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
    public function mapExpenseToItemContainer(ExpenseTransfer $expense)
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer): AbstractRequestContainer
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();
        $captureContainer = $this->mapOrderItems($orderTransfer, $captureContainer);
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
    protected function mapPaymentToAbstractAuthorization(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $authorizationContainer)
    {
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity)
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GetSecurityInvoiceContainer
     */
    public function mapGetSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer)
    {
        $getInvoiceContainer = new GetSecurityInvoiceContainer();
        $getInvoiceContainer->setInvoiceTitle($getSecurityInvoiceTransfer->getReference());

        return $getInvoiceContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity)
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\SecurityInvoiceContainer
     */
    protected function createPaymentMethodContainerFromPayment(SpyPaymentPayone $paymentEntity)
    {
        $paymentMethodContainer = new SecurityInvoiceContainer();

        return $paymentMethodContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneAuthorizationTransfer $payoneAuthorizationTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function createAuthorizationPersonalData(PayoneAuthorizationTransfer $payoneAuthorizationTransfer)
    {
        $personalContainer = new PersonalContainer();

        $personalContainer->setFirstName($payoneAuthorizationTransfer->getOrder()->getFirstName());
        $personalContainer->setLastName($payoneAuthorizationTransfer->getOrder()->getLastName());
        $personalContainer->setCountry($this->storeConfig->getCurrentCountry());

        return $personalContainer;
    }
}
