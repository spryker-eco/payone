<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface ExpressCheckoutHandlerInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function initPaypalExpressCheckout(): RedirectResponse;

    /**
     * @return void
     */
    public function loadExpressCheckoutDetails(): void;
}
