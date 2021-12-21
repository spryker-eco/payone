<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\Zed;

use Generated\Shared\Transfer\AddressCheckResponseTransfer;
use Generated\Shared\Transfer\ConsumerScoreResponseTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCancelRedirectTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class PayoneStub extends ZedRequestStub implements PayoneStubInterface
{
    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::statusUpdateAction()
     *
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatus
     *
     * @return \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer
     */
    public function updateStatus(PayoneTransactionStatusUpdateTransfer $transactionStatus): PayoneTransactionStatusUpdateTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/status-update',
            $transactionStatus,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::bankAccountCheckAction()
     *
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer): PayoneBankAccountCheckTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/bank-account-check',
            $bankAccountCheckTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::manageMandateAction()
     *
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(PayoneManageMandateTransfer $manageMandateTransfer): PayoneManageMandateTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/manage-mandate',
            $manageMandateTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::getFileAction()
     *
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $getFileTransfer): PayoneGetFileTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/get-file',
            $getFileTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::getPaymentDetailAction()
     *
     * @param \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer
     */
    public function getPaymentDetail(PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer): PayoneGetPaymentDetailTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/get-payment-detail',
            $getPaymentDetailTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::getInvoiceAction()
     *
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer): PayoneGetInvoiceTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/get-invoice',
            $getInvoiceTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::cancelRedirectAction()
     *
     * @param \Generated\Shared\Transfer\PayoneCancelRedirectTransfer $cancelRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneCancelRedirectTransfer
     */
    public function cancelRedirect(PayoneCancelRedirectTransfer $cancelRedirectTransfer): PayoneCancelRedirectTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/cancel-redirect',
            $cancelRedirectTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::initPaypalExpressCheckoutAction()
     *
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(
        PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
    ): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer {
        return $this->zedStub->call(
            '/payone/gateway/init-paypal-express-checkout',
            $requestTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::getPaypalExpressCheckoutDetailsAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
    {
        return $this->zedStub->call(
            '/payone/gateway/get-paypal-express-checkout-details',
            $quoteTransfer,
        );
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::sendAddressCheckRequestAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressCheckResponseTransfer
     */
    public function sendAddressCheckRequest(QuoteTransfer $quoteTransfer): AddressCheckResponseTransfer
    {
        return $this->zedStub->call('/payone/gateway/send-address-check-request', $quoteTransfer);
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::sendConsumerScoreRequestAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConsumerScoreResponseTransfer
     */
    public function sendConsumerScoreRequest(QuoteTransfer $quoteTransfer): ConsumerScoreResponseTransfer
    {
        return $this->zedStub->call('/payone/gateway/send-consumer-score-request', $quoteTransfer);
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::sendKlarnaStartSessionRequestAction()
     *
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function sendKlarnaStartSessionRequest(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ): PayoneKlarnaStartSessionResponseTransfer {
        return $this->zedStub->call('/payone/gateway/send-klarna-start-session-request', $payoneKlarnaStartSessionRequestTransfer);
    }
}
