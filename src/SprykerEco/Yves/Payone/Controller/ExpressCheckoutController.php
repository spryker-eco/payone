<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\HttpFoundation\Request;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function initPaypalExpressCheckoutAction(Request $request)
    {
        return $this->getFactory()->createCheckoutProcess()->process(
            $request
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function summaryAction(Request $request)
    {
        return $this->getFactory()->createCheckoutProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createSummaryFormCollection()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function placeOrderAction(Request $request)
    {
        return $this->getFactory()->createCheckoutProcess()->process(
            $request
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loadDetailsAction(Request $request)
    {
        return $this->getFactory()->createCheckoutProcess()->process(
            $request
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction(Request $request)
    {
        return $this->getFactory()->createCheckoutProcess()->process(
            $request
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $this->addErrorMessage('Checkout failed. Try again.');
        $this->resetPayment();
        return $this->redirectResponseInternal($this->getCartRoute());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function backAction()
    {
        return $this->redirectResponseInternal($this->getCartRoute());
    }

    /**
     * @return string
     */
    protected function getCartRoute()
    {
        return Config::get(PayoneConstants::PAYONE)[PayoneConstants::ROUTE_CART];
    }

    /**
     * @return void
     */
    protected function resetPayment()
    {
        $cartClient = $this->getFactory()
            ->getCartClient();
        $quote = $cartClient->getQuote();

        $quote->setPayment(null);
        $cartClient->storeQuote($quote);
    }
}
