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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerEco\Client\Payone\PayoneClientInterface getClient()
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function indexAction(Request $request)
    {
        $statusUpdateTranfer = new PayoneTransactionStatusUpdateTransfer();
        $statusUpdateTranfer->fromArray($request->request->all(), true);

        $response = $this->getClient()->updateStatus($statusUpdateTranfer)->getResponse();

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getFileAction(Request $request)
    {
        $customerClient = $this->getFactory()->createCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        if (!$customerTransfer) {
            return $this->redirectResponseInternal(PayoneControllerProvider::ROUTE_LOGIN);
        }

        $getFileTransfer = new PayoneGetFileTransfer();
        $getFileTransfer->setReference($request->query->get('id'));
        $getFileTransfer->setCustomerId($customerTransfer->getIdCustomer());

        $response = $this->getClient()->getFile($getFileTransfer);

        if ($response->getStatus() === 'ERROR') {
            return $this->viewResponse(['errormessage' => $response->getCustomerErrorMessage()]);
        }

        $callback = function () use ($response) {
            echo base64_decode($response->getRawResponse());
        };

        return $this->streamedResponse($callback, 200, ["Content-type" => "application/pdf"]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getInvoiceAction(Request $request)
    {
        $customerClient = $this->getFactory()->createCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        if (!$customerTransfer) {
            return $this->redirectResponseInternal(PayoneControllerProvider::ROUTE_LOGIN);
        }

        $getInvoiceTransfer = new PayoneGetInvoiceTransfer();
        $getInvoiceTransfer->setReference($request->query->get('id'));
        $getInvoiceTransfer->setCustomerId($customerTransfer->getIdCustomer());

        $response = $this->getClient()->getInvoice($getInvoiceTransfer);

        if ($response->getStatus() === 'ERROR') {
            return $this->viewResponse(['errormessage' => $response->getInternalErrorMessage()]);
        }

        $callback = function () use ($response) {
            echo base64_decode($response->getRawResponse());
        };

        return $this->streamedResponse($callback, 200, ["Content-type" => "application/pdf"]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function cancelRedirectAction(Request $request)
    {
        $orderReference = $request->query->get('orderReference');
        $urlHmac = $request->query->get('sig');

        if ($orderReference) {
            $cancelRedirectTransfer = new PayoneCancelRedirectTransfer();
            $cancelRedirectTransfer->setOrderReference($orderReference);
            $cancelRedirectTransfer->setUrlHmac($urlHmac);

            $response = $this->getClient()->cancelRedirect($cancelRedirectTransfer);
        }

        return $this->redirectResponseInternal(PayoneControllerProvider::CHECKOUT_PAYMENT);
    }

    /**
     * @return array
     */
    public function checkoutWithPaypalButtonAction()
    {
        return $this->viewResponse();
    }

    /**
     * @param callable|null $callback
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamedResponse($callback = null, $status = 200, $headers = [])
    {
        $streamedResponse = new StreamedResponse($callback, $status, $headers);
        $streamedResponse->send();

        return $streamedResponse;
    }

}
