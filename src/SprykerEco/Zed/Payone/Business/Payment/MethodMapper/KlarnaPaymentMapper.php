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
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class KlarnaPaymentMapper extends AbstractMapper implements KlarnaPaymentMapperInterface
{
    /**
     * @var string
     */
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface|\SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentPayoneEntity)
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();

        return $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $preAuthorizationContainer);
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    public function mapKlarnaPaymentToPreAuthorization(SpyPaymentPayone $paymentPayoneEntity, QuoteTransfer $quoteTransfer): PreAuthorizationContainerInterface
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $this->mapKlarnaPaymentToAbstractAuthorization($paymentPayoneEntity, $quoteTransfer, $preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentPayoneEntity, OrderTransfer $orderTransfer): AuthorizationContainerInterface
    {
        return new AuthorizationContainer();
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
        $captureContainer->setCaptureMode(PayoneApiConstants::CAPTURE_MODE_NOTCOMPLETED);
        if ($paymentPayoneEntity->getTransactionId() !== null) {
            $sequenceNumber = $this->getNextSequenceNumber($paymentPayoneEntity->getTransactionId());
            $captureContainer->setSequenceNumber($sequenceNumber);
        }

        return $captureContainer;
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
        $quoteTransfer = $payoneKlarnaStartSessionRequestTransfer->getQuoteOrFail();

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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface|\SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    protected function mapPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentPayoneEntity,
        AbstractAuthorizationContainer $authorizationContainer
    ) {
        $authorizationContainer->setAid($this->getStandardParameter()->getAid());

        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        if ($this->getStandardParameter()->getCurrency() !== null) {
            $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        }
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);

        $authorizationContainer->setReference($paymentPayoneEntity->getReference());

        $paydataContainer = new PaydataContainer();
        $authorizationContainer->setPaydata($paydataContainer);

        $personalContainer = $this->buildPersonalContainer($paymentPayoneEntity);
        $authorizationContainer->setPersonalData($personalContainer);
        $orderReference = $paymentPayoneEntity->getSpySalesOrder()->getOrderReference();
        $authorizationContainer->setRedirect($this->createRedirectContainer($orderReference));

        $shippingAddressEntity = $paymentPayoneEntity->getSpySalesOrder()->getShippingAddress();

        if ($shippingAddressEntity) {
            $shippingContainer = new ShippingContainer();
            $this->mapShippingAddressToShippingContainer($shippingContainer, $shippingAddressEntity);
        }

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    protected function mapKlarnaPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentPayoneEntity,
        QuoteTransfer $quoteTransfer,
        PreAuthorizationContainerInterface $authorizationContainer
    ): PreAuthorizationContainerInterface {
        $authorizationContainer->setAid($this->getStandardParameter()->getAid());

        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();
        $payoneKlarnaTransfer = $quoteTransfer->getPayment()->getPayoneKlarna();

        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        if ($this->getStandardParameter()->getCurrency() !== null) {
            $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        }
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $authorizationContainer->setFinancingType($payoneKlarnaTransfer->getPayMethod());

        $authorizationContainer->setReference($paymentPayoneEntity->getReference());

        $paydataContainer = new PaydataContainer();
        if ($payoneKlarnaTransfer->getPayMethodToken() !== null) {
            $paydataContainer->setAuthorizationToken($payoneKlarnaTransfer->getPayMethodToken());
        }
        $authorizationContainer->setPaydata($paydataContainer);

        $personalContainer = $this->buildPersonalContainer($paymentPayoneEntity);
        $authorizationContainer->setPersonalData($personalContainer);
        $orderReference = $paymentPayoneEntity->getSpySalesOrder()->getOrderReference();
        $authorizationContainer->setRedirect($this->createRedirectContainer($orderReference));

        $shippingAddressEntity = $paymentPayoneEntity->getSpySalesOrder()->getShippingAddress();

        if ($shippingAddressEntity) {
            $shippingContainer = new ShippingContainer();
            $this->mapShippingAddressToShippingContainer($shippingContainer, $shippingAddressEntity);
        }

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function buildPersonalContainer(SpyPaymentPayone $paymentPayoneEntity): ContainerInterface
    {
        $personalContainer = new PersonalContainer();

        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentPayoneEntity);
        $personalContainer->setCompany(null);
        $personalContainer->setEmail($paymentPayoneEntity->getSpySalesOrder()->getEmail());

        $currentRequest = $this->requestStack->getCurrentRequest();
        $personalContainer->setIp($currentRequest ? $currentRequest->getClientIp() : null);

        return $personalContainer;
    }
}
