<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Generated\Shared\Transfer\PayoneCancelRedirectTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Yves\Payone\Plugin\Provider\PayoneControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerEco\Client\Payone\PayoneClientInterface getClient()
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::CHECKOUT_SUCCESS
     *
     * @var string
     */
    protected const ROUTE_CHECKOUT_SUCCESS = 'checkout-success';

    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::CHECKOUT_ERROR
     *
     * @var string
     */
    protected const ROUTE_CHECKOUT_ERROR = 'checkout-error';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ORDER_REFERENCE = 'orderReference';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CHECKOUT_ERROR = 'checkout.step.error.text';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PAYMENT_ERROR = 'payone.payment.error';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function indexAction(Request $request): StreamedResponse
    {
        $statusUpdateTransfer = new PayoneTransactionStatusUpdateTransfer();
        $statusUpdateTransfer->fromArray($request->request->all(), true);

        $response = $this->getClient()->updateStatus($statusUpdateTransfer)->getResponse();

        $callback = function () use ($response): void {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function paymentSuccessAction(Request $request): RedirectResponse
    {
        $orderReference = $request->query->get(static::REQUEST_PARAM_ORDER_REFERENCE);
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();

        if (!$quoteTransfer->getOrderReference() || $quoteTransfer->getOrderReference() !== $orderReference) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CHECKOUT_ERROR);

            return $this->redirectResponseInternal(static::ROUTE_CHECKOUT_ERROR);
        }

        return $this->redirectResponseInternal(static::ROUTE_CHECKOUT_SUCCESS);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function paymentFailureAction(Request $request): RedirectResponse
    {
        $orderReference = $request->query->get(static::REQUEST_PARAM_ORDER_REFERENCE);
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();

        if (!$quoteTransfer->getOrderReference() || $quoteTransfer->getOrderReference() !== $orderReference) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CHECKOUT_ERROR);
        }

        $this->addErrorMessage(static::GLOSSARY_KEY_PAYMENT_ERROR);

        return $this->redirectResponseInternal(static::ROUTE_CHECKOUT_ERROR);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function getFileAction(Request $request)
    {
        $customerClient = $this->getFactory()->getCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        if (!$customerTransfer) {
            return $this->redirectResponseInternal(PayoneControllerProvider::ROUTE_LOGIN);
        }

        $getFileTransfer = new PayoneGetFileTransfer();
        $getFileTransfer->setReference((string)$request->query->get('id'));
        $getFileTransfer->setCustomerId($customerTransfer->getIdCustomer());

        $response = $this->getClient()->getFile($getFileTransfer);

        if ($response->getStatus() === 'ERROR') {
            return $this->viewResponse(['errormessage' => $response->getCustomerErrorMessage()]);
        }

        $callback = function () use ($response): void {
            echo base64_decode($response->getRawResponse());
        };

        return $this->streamedResponse($callback, 200, ['Content-type' => 'application/pdf']);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function getInvoiceAction(Request $request)
    {
        $customerClient = $this->getFactory()->getCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        if (!$customerTransfer) {
            return $this->redirectResponseInternal(PayoneControllerProvider::ROUTE_LOGIN);
        }

        $getInvoiceTransfer = new PayoneGetInvoiceTransfer();
        $getInvoiceTransfer->setReference((string)$request->query->get('id'));
        $getInvoiceTransfer->setCustomerId($customerTransfer->getIdCustomer());

        $response = $this->getClient()->getInvoice($getInvoiceTransfer);

        if ($response->getStatus() === 'ERROR') {
            return $this->viewResponse(['errormessage' => $response->getInternalErrorMessage()]);
        }

        $callback = function () use ($response): void {
            echo base64_decode($response->getRawResponse());
        };

        return $this->streamedResponse($callback, 200, ['Content-type' => 'application/pdf']);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function getSecurityInvoiceAction(Request $request)
    {
        $customerClient = $this->getFactory()->getCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        if (!$customerTransfer) {
            return $this->redirectResponseInternal(PayoneControllerProvider::ROUTE_LOGIN);
        }

        $getInvoiceTransfer = new PayoneGetInvoiceTransfer();
        $getInvoiceTransfer->setReference((string)$request->query->get('id'));
        $getInvoiceTransfer->setCustomerId($customerTransfer->getIdCustomer());

        $response = $this->getClient()->getInvoice($getInvoiceTransfer);

        if ($response->getStatus() === 'ERROR') {
            return $this->viewResponse(['errormessage' => $response->getInternalErrorMessage()]);
        }

        $callback = function () use ($response): void {
            echo base64_decode($response->getRawResponse());
        };

        return $this->streamedResponse($callback, 200, ['Content-type' => 'application/pdf']);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelRedirectAction(Request $request): Response
    {
        $orderReference = (string)$request->query->get('orderReference');
        $urlHmac = (string)$request->query->get('sig');

        if ($orderReference) {
            $cancelRedirectTransfer = new PayoneCancelRedirectTransfer();
            $cancelRedirectTransfer->setOrderReference($orderReference);
            $cancelRedirectTransfer->setUrlHmac($urlHmac);

            $response = $this->getClient()->cancelRedirect($cancelRedirectTransfer);
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer->setOrderReference(null);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        return $this->redirectResponseInternal(PayoneControllerProvider::CHECKOUT_PAYMENT);
    }

    /**
     * @param callable|null $callback
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamedResponse(?callable $callback = null, int $status = 200, array $headers = []): StreamedResponse
    {
        $streamedResponse = new StreamedResponse($callback, $status, $headers);
        $streamedResponse->send();

        return $streamedResponse;
    }
}
