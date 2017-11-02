<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

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
    public function initPaypalExpressCheckoutAction()
    {
        $expressCheckoutHandler = $this->getFactory()->createExpressCheckoutHandler();
        return $expressCheckoutHandler->initPaypalExpressCheckout();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loadPaypalExpressCheckoutDetailsAction()
    {
        $expressCheckoutHandler = $this->getFactory()->createExpressCheckoutHandler();
        $expressCheckoutHandler->loadExpressCheckoutDetails();
        return $this->redirectResponseInternal(
            $this->getFactory()
                ->getConfig()
                ->getStandardCheckoutEntryPoint()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $this->addErrorMessage('Paypal transaction failed.');
        return $this->redirectResponseInternal(
            $this->getFactory()
                ->getConfig()
                ->getFailureProjectRoute()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function backAction()
    {
        return $this->redirectResponseInternal(
            $this->getFactory()
            ->getConfig()
            ->getBackProjectRoute()
        );
    }
}
