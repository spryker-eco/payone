<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\ExpressCheckoutContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface;
use SprykerEco\Zed\Payone\Business\Payment\GenericPaymentMethodMapperInterface;

class GenericPayment extends AbstractMapper implements GenericPaymentMethodMapperInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer $genericPayment
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function mapRequestTransferToGenericPayment(
        GenericPaymentContainer $genericPayment,
        PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
    ): GenericPaymentContainer {
        $quoteTransfer = $requestTransfer->getQuote();

        $genericPayment = $this->mapQuoteTransferToGenericPayment($genericPayment, $quoteTransfer);
        $genericPayment->setSuccessUrl($requestTransfer->getSuccessUrl());
        $genericPayment->setBackUrl($requestTransfer->getBackUrl());
        $genericPayment->setErrorUrl($requestTransfer->getFailureUrl());

        return $genericPayment;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer $genericPayment
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function mapQuoteTransferToGenericPayment(
        GenericPaymentContainer $genericPayment,
        QuoteTransfer $quoteTransfer
    ): GenericPaymentContainer {
        $genericPayment->setAmount((int)$quoteTransfer->getTotals()->getGrandTotal());
        $genericPayment->setWorkOrderId(
            (string)$quoteTransfer->getPayment()
                ->getPayonePaypalExpressCheckout()->getWorkOrderId(),
        );

        return $genericPayment;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function createBaseGenericPaymentContainer(): GenericPaymentContainer
    {
        $genericPayment = new GenericPaymentContainer();
        $paydataContainer = new PaydataContainer();
        $genericPayment->setPaydata($paydataContainer);

        $genericPayment->setAid($this->getStandardParameter()->getAid());
        $genericPayment->setClearingType(PayoneApiConstants::CLEARING_TYPE_E_WALLET);
        $genericPayment->setCurrency((string)$this->getStandardParameter()->getCurrency());
        $genericPayment->setWalletType(PayoneApiConstants::E_WALLET_TYPE_PAYPAL);

        return $genericPayment;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    public function mapPaymentToPreauthorization(SpyPaymentPayone $paymentPayoneEntity): PreAuthorizationContainerInterface
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer $preAuthorizationContainer */
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $preAuthorizationContainer);

        $preAuthorizationContainer->setWorkOrderId((string)$paymentPayoneEntity->getSpyPaymentPayoneDetail()->getWorkOrderId());

        return $preAuthorizationContainer;
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
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_E_WALLET);
        $authorizationContainer->setReference($paymentPayoneEntity->getReference());
        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        $authorizationContainer->setCurrency((string)$this->getStandardParameter()->getCurrency());
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainerFromPayment($paymentPayoneEntity));

        $personalContainer = new PersonalContainer();
        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentPayoneEntity);
        $authorizationContainer->setPersonalData($personalContainer);

        $shippingContainer = new ShippingContainer();
        $shippingAddressEntity = $paymentPayoneEntity->getSpySalesOrder()->getShippingAddress();
        if ($shippingAddressEntity) {
            $this->mapShippingAddressToShippingContainer($shippingContainer, $shippingAddressEntity);
        }
        $authorizationContainer->setShippingData($shippingContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\ExpressCheckoutContainer
     */
    protected function createPaymentMethodContainerFromPayment(
        SpyPaymentPayone $paymentPayoneEntity
    ): ExpressCheckoutContainer {
        $paymentMethodContainer = new ExpressCheckoutContainer();
        $paymentMethodContainer->setRedirect($this->createRedirectContainer($paymentPayoneEntity->getSpySalesOrder()->getOrderReference()));

        $paymentMethodContainer->setWalletType((string)$paymentPayoneEntity->getSpyPaymentPayoneDetail()->getType());

        return $paymentMethodContainer;
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

        return $authorizationContainer;
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
        $captureContainer->setSequenceNumber($this->getNextSequenceNumber((int)$paymentPayoneEntity->getTransactionId()));

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
        $debitContainer->setSequenceNumber($this->getNextSequenceNumber((int)$paymentPayoneEntity->getTransactionId()));
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
        $refundContainer->setSequenceNumber($this->getNextSequenceNumber((int)$paymentPayoneEntity->getTransactionId()));
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());

        return $refundContainer;
    }
}
