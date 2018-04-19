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
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
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
    ) {

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
    ) {

        $genericPayment->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $genericPayment->setWorkOrderId(
            $quoteTransfer->getPayment()
                ->getPayonePaypalExpressCheckout()->getWorkOrderId()
        );
        return $genericPayment;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function createBaseGenericPaymentContainer()
    {
        $genericPayment = new GenericPaymentContainer();
        $paydataContainer = new PaydataContainer();
        $genericPayment->setPaydata($paydataContainer);

        $genericPayment->setAid($this->getStandardParameter()->getAid());
        $genericPayment->setClearingType(PayoneApiConstants::CLEARING_TYPE_E_WALLET);
        $genericPayment->setCurrency($this->getStandardParameter()->getCurrency());
        $genericPayment->setWalletType(PayoneApiConstants::E_WALLET_TYPE_PAYPAL);

        return $genericPayment;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer
     */
    public function mapPaymentToPreauthorization(SpyPaymentPayone $paymentEntity)
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);

        $preAuthorizationContainer->setWorkOrderId($paymentEntity->getSpyPaymentPayoneDetail()->getWorkOrderId());

        return $preAuthorizationContainer;
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
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_E_WALLET);
        $authorizationContainer->setReference($paymentEntity->getReference());
        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainerFromPayment($paymentEntity));

        $personalContainer = new PersonalContainer();
        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentEntity);
        $authorizationContainer->setPersonalData($personalContainer);

        $shippingAddressEntity = $paymentEntity->getSpySalesOrder()->getShippingAddress();
        $shippingContainer = new ShippingContainer();
        $this->mapShippingAddressToShippingContainer($shippingContainer, $shippingAddressEntity);
        $authorizationContainer->setShippingData($shippingContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\EWalletContainer
     */
    protected function createPaymentMethodContainerFromPayment(SpyPaymentPayone $paymentEntity)
    {
        $paymentMethodContainer = new ExpressCheckoutContainer();
        $paymentMethodContainer->setRedirect($this->createRedirectContainer($paymentEntity->getSpySalesOrder()->getOrderReference()));

        $paymentMethodContainer->setWalletType($paymentEntity->getSpyPaymentPayoneDetail()->getType());

        return $paymentMethodContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer)
    {
        $authorizationContainer = new AuthorizationContainer();
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
        $captureContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));

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
}
