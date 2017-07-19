<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneCancelRedirectTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Yves\Payone\Plugin\Provider\PayoneControllerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerEco\Client\Payone\PayoneClientInterface getClient()
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class CheckoutController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function startPaypalExpressCheckoutAction()
    {
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();
        $this->addExpressCheckoutPaymentToQuote($quoteTransfer);

        $response = $this->getClient()->startPaypalExpressCheckout($quoteTransfer);

        $quoteTransfer->getPayment()->getPayonePaypalExpressCheckout()->setWorkOrderId(
            $response->getWorkOrderId()
        );
        $this->getFactory()->getCartClient()->storeQuote($quoteTransfer);

        return $this->redirectResponseExternal($response->getRedirectUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addExpressCheckoutPaymentToQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = new PaymentTransfer();
        $paypalExpressChecoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressChecoutPayment);
        $quoteTransfer->setPayment($paymentTransfer);
    }

}
