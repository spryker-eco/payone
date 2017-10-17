<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Pyz\Yves\Shipment\Form\ShipmentForm;
use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Plugin\Provider\PayoneControllerProvider;
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
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
     * @return array
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
        $this->addErrorMessage('Paypal transaction failed.');
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

}
