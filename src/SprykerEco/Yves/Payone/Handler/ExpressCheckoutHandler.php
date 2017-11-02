<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Handler;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Checkout\CheckoutClientInterface;
use SprykerEco\Client\Payone\PayoneClientInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator;
use SprykerEco\Yves\Payone\PayoneConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExpressCheckoutHandler implements ExpressCheckoutHandlerInterface
{
    /**
     * @var \SprykerEco\Client\Payone\PayoneClientInterface
     */
    protected $payoneClient;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\Checkout\CheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator
     */
    protected $quoteHydrator;

    /**
     * @var \SprykerEco\Yves\Payone\PayoneConfig
     */
    protected $payoneConfig;

    /**
     * @param \SprykerEco\Client\Payone\PayoneClientInterface $payoneClient
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \Spryker\Client\Checkout\CheckoutClientInterface $checkoutClient
     * @param \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator $quoteHydrator
     * @param \SprykerEco\Yves\Payone\PayoneConfig $payoneConfig
     */
    public function __construct(
        PayoneClientInterface $payoneClient,
        CartClientInterface $cartClient,
        CheckoutClientInterface $checkoutClient,
        QuoteHydrator $quoteHydrator,
        PayoneConfig $payoneConfig
    ) {

        $this->payoneClient = $payoneClient;
        $this->cartClient = $cartClient;
        $this->checkoutClient = $checkoutClient;
        $this->quoteHydrator = $quoteHydrator;
        $this->payoneConfig = $payoneConfig;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function initPaypalExpressCheckout()
    {
        $initExpressCheckoutRequest = $this->prepareInitExpressCheckoutRequest();
        $response = $this->payoneClient->initPaypalExpressCheckout($initExpressCheckoutRequest);

        $quoteTransfer = $initExpressCheckoutRequest->getQuote();
        $quoteTransfer->getPayment()->getPayonePaypalExpressCheckout()->setWorkOrderId(
            $response->getWorkOrderId()
        );
        $this->cartClient->storeQuote($quoteTransfer);

        return new RedirectResponse($response->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function loadExpressCheckoutDetails()
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $details = $this->payoneClient->getPaypalExpressCheckoutDetails($quoteTransfer);
        $quoteTransfer = $this->quoteHydrator->getHydratedQuote($quoteTransfer, $details);
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToCheckoutEntryPoint()
    {
        return new RedirectResponse(
            $this->payoneConfig->getStandardCheckoutEntryPoint()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToFailureUrl()
    {
        return new RedirectResponse(
            $this->payoneConfig->getFailureProjectUrl()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToBackUrl()
    {
        return new RedirectResponse(
            $this->payoneConfig->getBackProjectUrl()
        );
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
            $this->payoneConfig->getSuccessUrl()
        );
        $initExpressCheckoutRequest->setFailureUrl(
            $this->payoneConfig->getFailureUrl()
        );
        $initExpressCheckoutRequest->setBackUrl(
            $this->payoneConfig->getBackUrl()
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
