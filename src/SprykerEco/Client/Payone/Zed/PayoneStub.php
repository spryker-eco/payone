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
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class PayoneStub extends ZedRequestStub implements PayoneStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatus
     *
     * @return \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer
     */
    public function updateStatus(PayoneTransactionStatusUpdateTransfer $transactionStatus)
    {
        return $this->zedStub->call(
            '/payone/gateway/status-update',
            $transactionStatus
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/bank-account-check',
            $bankAccountCheckTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(PayoneManageMandateTransfer $manageMandateTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/manage-mandate',
            $manageMandateTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $getFileTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/get-file',
            $getFileTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer
     */
    public function getPaymentDetail(PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/get-payment-detail',
            $getPaymentDetailTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/get-invoice',
            $getInvoiceTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneCancelRedirectTransfer $cancelRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneCancelRedirectTransfer
     */
    public function cancelRedirect(PayoneCancelRedirectTransfer $cancelRedirectTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/cancel-redirect',
            $cancelRedirectTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/init-paypal-express-checkout',
            $requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call(
            '/payone/gateway/get-paypal-express-checkout-details',
            $quoteTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressCheckResponseTransfer
     */
    public function sendAddressCheckRequest(QuoteTransfer $quoteTransfer): AddressCheckResponseTransfer
    {
        return $this->zedStub->call('/payone/gateway/send-address-check-request', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConsumerScoreResponseTransfer
     */
    public function sendConsumerScoreRequest(QuoteTransfer $quoteTransfer): ConsumerScoreResponseTransfer
    {
        return $this->zedStub->call('/payone/gateway/send-consumer-score-request', $quoteTransfer);
    }

    /**
     * @uses \SprykerEco\Zed\Payone\Communication\Controller\GatewayController::startKlarnaSessionAction()
     *
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function startKlarnaSession(PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer): PayoneKlarnaStartSessionResponseTransfer
    {
        return $this->zedStub->call('/payone/gateway/start-klarna-session', $payoneKlarnaStartSessionRequestTransfer);
    }
}
