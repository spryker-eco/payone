<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;

class PayoneInitPaypalExpressCheckoutMethodSender implements PayoneInitPaypalExpressCheckoutMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface
     */
    protected $genericRequestMethodSender;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface $genericRequestMethodSender
     */
    public function __construct(
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneGenericRequestMethodSenderInterface $genericRequestMethodSender
    ) {
        $this->paymentMapperReader = $paymentMapperReader;
        $this->genericRequestMethodSender = $genericRequestMethodSender;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(
        PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
    ): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer {
        /** @var \SprykerEco\Zed\Payone\Business\Payment\GenericPaymentMethodMapperInterface $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper(
            PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT
        );
        $baseGenericPaymentContainer = $paymentMethodMapper->createBaseGenericPaymentContainer();
        $baseGenericPaymentContainer->getPaydata()->setAction(PayoneApiConstants::PAYONE_EXPRESS_CHECKOUT_SET_ACTION);
        $requestContainer = $paymentMethodMapper->mapRequestTransferToGenericPayment(
            $baseGenericPaymentContainer,
            $requestTransfer
        );
        $responseTransfer = $this->genericRequestMethodSender->performGenericRequest($requestContainer);

        return $responseTransfer;
    }
}
