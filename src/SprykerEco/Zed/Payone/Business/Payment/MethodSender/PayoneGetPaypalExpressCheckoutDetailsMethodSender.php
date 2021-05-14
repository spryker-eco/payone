<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;

class PayoneGetPaypalExpressCheckoutDetailsMethodSender implements PayoneGetPaypalExpressCheckoutDetailsMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager
     */
    protected $paymentMapperManager;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface
     */
    protected $genericRequestMethodSender;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface $genericRequestMethodSender
     */
    public function __construct(
        PaymentMapperManager $paymentMapperManager,
        PayoneGenericRequestMethodSenderInterface $genericRequestMethodSender
    ) {
        $this->paymentMapperManager = $paymentMapperManager;
        $this->genericRequestMethodSender = $genericRequestMethodSender;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
    {
        /** @var \SprykerEco\Zed\Payone\Business\Payment\GenericPaymentMethodMapperInterface $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperManager->getRegisteredPaymentMethodMapper(
            PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT
        );

        $baseGenericPaymentContainer = $paymentMethodMapper->createBaseGenericPaymentContainer();
        $baseGenericPaymentContainer->getPaydata()->setAction(
            PayoneApiConstants::PAYONE_EXPRESS_CHECKOUT_GET_DETAILS_ACTION
        );
        $requestContainer = $paymentMethodMapper->mapQuoteTransferToGenericPayment(
            $baseGenericPaymentContainer,
            $quoteTransfer
        );
        $responseTransfer = $this->genericRequestMethodSender->performGenericRequest($requestContainer);

        return $responseTransfer;
    }
}
