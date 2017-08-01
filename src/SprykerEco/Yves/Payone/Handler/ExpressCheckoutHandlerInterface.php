<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Handler;

interface ExpressCheckoutHandlerInterface
{

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function initPaypalExpressCheckout();

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder();

    /**
     * @return void
     */
    public function clearQuote();

}
