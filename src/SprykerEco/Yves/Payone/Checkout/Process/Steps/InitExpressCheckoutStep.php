<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Checkout\Process\Steps;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\AbstractBaseStep;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use SprykerEco\Client\Payone\PayoneClientInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Shared\Config\Config;

class InitExpressCheckoutStep extends AbstractBaseStep implements StepWithExternalRedirectInterface
{

    /**
     * @var \SprykerEco\Client\Payone\PayoneClientInterface
     */
    protected $payoneClient;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    public function __construct(
        PayoneClientInterface $payoneClient,
        CartClientInterface $cartClient,
        $stepRoute,
        $escapeRoute
    )
    {
        parent::__construct($stepRoute, $escapeRoute);
        $this->payoneClient = $payoneClient;
        $this->cartClient = $cartClient;
    }

    /**
     * Requirements for this step, return true when satisfied.
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $dataTransfer)
    {
        return true;
    }

    /**
     * Require input, should we render view with form or just skip step after calling execute.
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer)
    {
       return false;
    }

    /**
     * Execute step logic, happens after form submit if provided, gets AbstractTransfer filled by form data.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $dataTransfer)
    {
        $initExpressCheckoutRequest = $this->prepareInitExpressCheckoutRequest();
        $response = $this->payoneClient->initPaypalExpressCheckout($initExpressCheckoutRequest);

        $quoteTransfer = $initExpressCheckoutRequest->getQuote();
        $payment = $quoteTransfer->getPayment()->getPayonePaypalExpressCheckout();
        $payment->setWorkOrderId($response->getWorkOrderId());
        $payment->setExternalRedirectUrl($response->getRedirectUrl());
        $this->cartClient->storeQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Conditions that should be met for this step to be marked as completed. returns true when satisfied.
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $dataTransfer)
    {
        return true;
    }

    /**
     * Return external redirect url, when redirect occurs not within same application. Used after execute.
     *
     * @return string
     */
    public function getExternalRedirectUrl()
    {
        $quoteTransfer = $this->cartClient->getQuote();

        return $quoteTransfer
            ->getPayment()
            ->getPayonePaypalExpressCheckout()
            ->getExternalRedirectUrl();
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer
     */
    protected function prepareInitExpressCheckoutRequest()
    {
        $initExpressCheckoutRequest = new PayoneInitPaypalExpressCheckoutRequestTransfer();
        $quoteTransfer = $this->cartClient->getQuote();
        $this->addExpressCheckoutPaymentToQuote($quoteTransfer);

        $initExpressCheckoutRequest->setQuote($quoteTransfer);
        $initExpressCheckoutRequest->setSuccessUrl(
            Config::get(PayoneConstants::PAYONE)[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_SUCCESS_URL]
        );
        $initExpressCheckoutRequest->setFailureUrl(
            Config::get(PayoneConstants::PAYONE)[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_FAILURE_URL]
        );
        $initExpressCheckoutRequest->setBackUrl(
            Config::get(PayoneConstants::PAYONE)[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_BACK_URL]
        );

        return $initExpressCheckoutRequest;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addExpressCheckoutPaymentToQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider(PayoneConstants::PROVIDER_NAME);
        $paypalExpressCheckoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressCheckoutPayment);
        $quoteTransfer->setPayment($paymentTransfer);
    }
}