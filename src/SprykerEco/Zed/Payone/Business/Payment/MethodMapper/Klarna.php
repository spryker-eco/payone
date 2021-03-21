<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Pyz\Shared\Shipment\ShipmentConfig;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaPreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use Symfony\Component\HttpFoundation\RequestStack;

class Klarna extends AbstractMapper
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    public function __construct(Store $storeConfig, RequestStack $requestStack)
    {
        parent::__construct($storeConfig);

        $this->requestStack = $requestStack;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_KLARNA;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaPreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity)
    {
        $preAuthorizationContainer = new KlarnaPreAuthorizationContainer();

        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer)
    {
        $authorizationContainer = new KlarnaAuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity)
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();

        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $captureContainer->setTxid($paymentEntity->getTransactionId());
        $captureContainer->setCapturemode(PayoneApiConstants::CAPTURE_MODE_COMPLETED);

        return $captureContainer;
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

        return $refundContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer
     */
    public function mapPaymentToStartSession(PayoneKlarnaStartSessionRequestTransfer $klarnaStartSessionRequestTransfer): KlarnaGenericPaymentContainer
    {
        $quoteTransfer = $klarnaStartSessionRequestTransfer->getQuote();

        $klarnaGenericPaymentContainer = new KlarnaGenericPaymentContainer();

        $klarnaGenericPaymentContainer->setAid($this->getStandardParameter()->getAid());
        $klarnaGenericPaymentContainer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $klarnaGenericPaymentContainer->setCurrency($quoteTransfer->getCurrency()->getCode());
        $klarnaGenericPaymentContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $klarnaGenericPaymentContainer->setFinancingtype($klarnaStartSessionRequestTransfer->getPayMethod());

        $this->prepareOrderItems($quoteTransfer, $klarnaGenericPaymentContainer);

        $paydataContainer = new PaydataContainer();
        $paydataContainer->setAction(PayoneApiConstants::PAYMENT_KLARNA_START_SESSION_ACTION);
        $klarnaGenericPaymentContainer->setPaydata($paydataContainer);

        $personalContainer = $this->createPersonalContainerFromQuoteTransfer($quoteTransfer);
        $klarnaGenericPaymentContainer->setPersonalData($personalContainer);

        return $klarnaGenericPaymentContainer;
    }

    protected function createPersonalContainerFromQuoteTransfer(QuoteTransfer $quoteTransfer): PersonalContainer
    {
        $personalContainer = new PersonalContainer();
        $billingAddress = $quoteTransfer->getBillingAddress();
        $personalContainer->setEmail($billingAddress->getEmail());
        $personalContainer->setCity($billingAddress->getCity());

        $personalContainer->setCountry($this->storeConfig->getCurrentCountry());
        $personalContainer->setFirstName($billingAddress->getFirstName());
        $personalContainer->setLastName($billingAddress->getLastName());
        $personalContainer->setSalutation($billingAddress->getSalutation());

        $personalContainer->setTelephoneNumber($billingAddress->getPhone());
        $personalContainer->setLanguage($this->getStandardParameter()->getLanguage());

        return $personalContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    protected function mapPaymentToAbstractAuthorization(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $authorizationContainer)
    {
        $authorizationContainer->setAid($this->getStandardParameter()->getAid());

        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $authorizationContainer->setFinancingtype($paymentDetailEntity->getPayMethod());
        $authorizationContainer->setLanguage($this->getStandardParameter()->getLanguage());

        $authorizationContainer->setReference($paymentEntity->getReference());

        $paydataContainer = new PaydataContainer();
        $paydataContainer->setAuthorizationToken($paymentDetailEntity->getTokenList());
        $authorizationContainer->setPaydata($paydataContainer);

        $personalContainer = $this->buildPersonalContainer($paymentEntity);
        $authorizationContainer->setPersonalData($personalContainer);
        $orderReference = $paymentEntity->getSpySalesOrder()->getOrderReference();
        $authorizationContainer->setRedirect($this->createRedirectContainer($orderReference));

        $shippingAddressEntity = $paymentEntity->getSpySalesOrder()->getShippingAddress();
        $shippingContainer = new ShippingContainer();
        $this->mapShippingAddressToShippingContainer($shippingContainer, $shippingAddressEntity);

//        $authorizationContainer->setShippingData($shippingContainer); // TODO

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function buildPersonalContainer(SpyPaymentPayone $paymentEntity): PersonalContainer
    {
        $personalContainer = new PersonalContainer();

        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentEntity);

        $personalContainer->setLanguage($this->getStandardParameter()->getLanguage());
        $currentRequest = $this->requestStack->getCurrentRequest();
        $personalContainer->setIp($currentRequest->getClientIp());

        return $personalContainer;
    }

    protected function prepareOrderItems(QuoteTransfer $quoteTransfer, AbstractRequestContainer $container): AbstractRequestContainer
    {
        $arrayIt = [];
        $arrayId = [];
        $arrayPr = [];
        $arrayNo = [];
        $arrayDe = [];
        $arrayVa = [];

        $key = 1;

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS;
            $arrayId[$key] = $itemTransfer->getSku();
            $arrayPr[$key] = $itemTransfer->getSumGrossPrice();
            $arrayNo[$key] = (int)$itemTransfer->getQuantity();
            $arrayDe[$key] = $itemTransfer->getName();
            $arrayVa[$key] = (int)$itemTransfer->getTaxRate();
            $key++;
        }

        $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_SHIPMENT;
        $arrayId[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_SHIPMENT;
        $arrayPr[$key] = $this->getDeliveryCosts($quoteTransfer);
        $arrayNo[$key] = 1;
        $arrayDe[$key] = 'Shipment';
        $arrayVa[$key] = 0;

        $container->setIt($arrayIt);
        $container->setId($arrayId);
        $container->setPr($arrayPr);
        $container->setNo($arrayNo);
        $container->setDe($arrayDe);
        $container->setVa($arrayVa);

        return $container;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getDeliveryCosts(QuoteTransfer $quoteTransfer): int
    {
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                return $expense->getSumGrossPrice();
            }
        }

        return 0;
    }
}
