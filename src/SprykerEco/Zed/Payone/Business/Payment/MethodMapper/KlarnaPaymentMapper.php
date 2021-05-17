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
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractPayoneKlarnaAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaPreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use Symfony\Component\HttpFoundation\RequestStack;

class KlarnaPaymentMapper extends AbstractMapper implements KlarnaPaymentMapperInterface
{
    protected const STREET_ADDRESS_SEPARATOR = ' ';

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(Store $storeConfig, RequestStack $requestStack)
    {
        parent::__construct($storeConfig);

        $this->requestStack = $requestStack;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return PayoneApiConstants::PAYMENT_METHOD_KLARNA;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaPreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity): ContainerInterface
    {
        $preAuthorizationContainer = new KlarnaPreAuthorizationContainer();
        $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer): ContainerInterface
    {
        $authorizationContainer = new KlarnaAuthorizationContainer();
        $this->mapPaymentToAbstractAuthorization($paymentEntity, $authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity): ContainerInterface
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();
        $captureContainer = new CaptureContainer();

        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $captureContainer->setTxid($paymentEntity->getTransactionId());
        $captureContainer->setCaptureMode(PayoneApiConstants::CAPTURE_MODE_NOTCOMPLETED);
        $sequenceNumber = $this->getNextSequenceNumber($paymentEntity->getTransactionId());
        $captureContainer->setSequenceNumber($sequenceNumber);

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity): ContainerInterface
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
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity): ContainerInterface
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($paymentEntity->getTransactionId());
        $refundContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());

        return $refundContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer
     */
    public function mapPaymentToKlarnaGenericPaymentContainer(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ): ContainerInterface {
        $quoteTransfer = $payoneKlarnaStartSessionRequestTransfer->getQuote();

        $klarnaGenericPaymentContainer = new KlarnaGenericPaymentContainer();

        $klarnaGenericPaymentContainer->setAid($this->getStandardParameter()->getAid());
        $klarnaGenericPaymentContainer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $klarnaGenericPaymentContainer->setCurrency($quoteTransfer->getCurrency()->getCode());
        $klarnaGenericPaymentContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $klarnaGenericPaymentContainer->setFinancingType($payoneKlarnaStartSessionRequestTransfer->getPayMethod());

        $paydataContainer = new PaydataContainer();
        $paydataContainer->setAction(PayoneApiConstants::PAYMENT_KLARNA_START_SESSION_ACTION);
        $klarnaGenericPaymentContainer->setPaydata($paydataContainer);

        $personalContainer = $this->createPersonalContainerFromQuoteTransfer($quoteTransfer);
        $klarnaGenericPaymentContainer->setPersonalData($personalContainer);

        return $klarnaGenericPaymentContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function createPersonalContainerFromQuoteTransfer(QuoteTransfer $quoteTransfer): ContainerInterface
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
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractPayoneKlarnaAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractPayoneKlarnaAuthorizationContainer
     */
    protected function mapPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentEntity,
        AbstractPayoneKlarnaAuthorizationContainer $authorizationContainer
    ): ContainerInterface {
        $authorizationContainer->setAid($this->getStandardParameter()->getAid());

        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $authorizationContainer->setFinancingType($paymentDetailEntity->getPayMethod());

        $authorizationContainer->setReference($paymentEntity->getReference());

        $paydataContainer = new PaydataContainer();
        $paydataContainer->setAuthorizationToken($paymentDetailEntity->getToken());
        $authorizationContainer->setPaydata($paydataContainer);

        $personalContainer = $this->buildPersonalContainer($paymentEntity);
        $authorizationContainer->setPersonalData($personalContainer);
        $orderReference = $paymentEntity->getSpySalesOrder()->getOrderReference();
        $authorizationContainer->setRedirect($this->createRedirectContainer($orderReference));

        $shippingAddressEntity = $paymentEntity->getSpySalesOrder()->getShippingAddress();

        if ($shippingAddressEntity) {
            $shippingContainer = new ShippingContainer();
            $this->mapShippingAddressToShippingContainer($shippingContainer, $shippingAddressEntity);
        }

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function buildPersonalContainer(SpyPaymentPayone $paymentEntity): ContainerInterface
    {
        $personalContainer = new PersonalContainer();

        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentEntity);

        $currentRequest = $this->requestStack->getCurrentRequest();
        $personalContainer->setIp($currentRequest->getClientIp());

        return $personalContainer;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalContainer
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return void
     */
    protected function mapBillingAddressToPersonalContainer(
        PersonalContainer $personalContainer,
        SpyPaymentPayone $paymentEntity
    ): void {
        $orderEntity = $paymentEntity->getSpySalesOrder();
        $billingAddressEntity = $orderEntity->getBillingAddress();
        $personalContainer->setCountry($billingAddressEntity->getCountry()->getIso2Code());
        $personalContainer->setFirstName($billingAddressEntity->getFirstName());
        $personalContainer->setLastName($billingAddressEntity->getLastName());
        $personalContainer->setSalutation($billingAddressEntity->getSalutation());
        $personalContainer->setStreet(implode(self::STREET_ADDRESS_SEPARATOR, [$billingAddressEntity->getAddress1(), $billingAddressEntity->getAddress2()]));
        $personalContainer->setAddressAddition($billingAddressEntity->getAddress3());
        $personalContainer->setZip($billingAddressEntity->getZipCode());
        $personalContainer->setCity($billingAddressEntity->getCity());
        $personalContainer->setEmail($billingAddressEntity->getEmail());
        $personalContainer->setTelephoneNumber($billingAddressEntity->getPhone());
        $personalContainer->setLanguage($this->getStandardParameter()->getLanguage());
        $personalContainer->setPersonalId($orderEntity->getCustomerReference());
        $personalContainer->setEmail($orderEntity->getEmail());
    }
}
