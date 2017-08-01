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
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Shared\Config\Config;
use SprykerEco\Client\Payone\PayoneClientInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExpressCheckoutHandler implements ExpressCheckoutHandlerInterface
{

    /**
     * @const PAYMENT_PROVIDER
     */
    const PAYMENT_PROVIDER = 'Payone';

    /**
     * @var \SprykerEco\Client\Payone\PayoneClientInterface
     */
    protected $payoneClient;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\Checkout\CheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator
     */
    protected $quoteHydrator;

    /**
     * @param \SprykerEco\Client\Payone\PayoneClientInterface $payoneClient
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     * @param \Spryker\Client\Checkout\CheckoutClientInterface $checkoutClient
     * @param \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator $quoteHydrator
     */
    public function __construct(
        PayoneClientInterface $payoneClient,
        CartClientInterface $cartClient,
        CustomerClientInterface $customerClient,
        CheckoutClientInterface $checkoutClient,
        QuoteHydrator $quoteHydrator
    ) {

        $this->payoneClient = $payoneClient;
        $this->cartClient = $cartClient;
        $this->customerClient = $customerClient;
        $this->checkoutClient = $checkoutClient;
        $this->quoteHydrator = $quoteHydrator;
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
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder()
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $details = $this->payoneClient->getPaypalExpressCheckoutDetails($quoteTransfer);
        $quoteTransfer = $this->quoteHydrator->getHydratedQuote($quoteTransfer, $details);

        return $this->checkoutClient->placeOrder($quoteTransfer);
    }

    /**
     * @return void
     */
    public function clearQuote()
    {
        $this->customerClient->markCustomerAsDirty();
        $this->cartClient->clearQuote();
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
        $paymentTransfer->setPaymentProvider(static::PAYMENT_PROVIDER);
        $paypalExpressCheckoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressCheckoutPayment);
        $quoteTransfer->setPayment($paymentTransfer);
    }

}
