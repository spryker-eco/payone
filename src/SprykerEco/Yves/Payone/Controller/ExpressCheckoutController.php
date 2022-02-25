<?php
// phpcs:disable SprykerStrict.TypeHints.ReturnTypeHint.MissingNativeTypeHint

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

        return $expressCheckoutHandler->redirectToCheckoutEntryPoint();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction()
    {
        $this->addErrorMessage('Paypal transaction failed.');

        return $this->getFactory()
            ->createExpressCheckoutHandler()
            ->redirectToFailureUrl();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function backAction()
    {
        return $this->getFactory()
            ->createExpressCheckoutHandler()
            ->redirectToBackUrl();
    }
}
