<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Pyz\Yves\Cart\Plugin\Provider\CartControllerProvider;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Yves\Payone\Plugin\Provider\PayoneControllerProvider;

/**
 * @method \SprykerEco\Client\Payone\PayoneClientInterface getClient()
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class ExpressCheckoutController extends AbstractController
{

    /**
     * @return array
     */
    public function checkoutWithPaypalButtonAction()
    {
        return $this->viewResponse();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function startPaypalExpressCheckoutAction()
    {
        $expressCheckoutHandler = $this->getFactory()->createExpressCheckoutHandler();
        return $expressCheckoutHandler->startPaypalExpressCheckout();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction()
    {
        $expressCheckoutHandler = $this->getFactory()->createExpressCheckoutHandler();

        $checkoutResponseTransfer = $expressCheckoutHandler->placeOrder();
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->redirectResponseInternal(PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE);
        }
        $expressCheckoutHandler->clearQuote();

    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $this->addErrorMessage('Paypal transaction failed.');
        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function backAction()
    {
        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

}
