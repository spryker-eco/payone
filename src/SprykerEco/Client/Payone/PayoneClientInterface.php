<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone;

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
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PayoneClientInterface
{
    /**
     * Specification:
     * - Prepares credit card check request to bring standard parameters and hash to front-end.
     *
     * @api
     *
     * @return \SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainer
     */
    public function getCreditCardCheckRequest();

    /**
     * Specification:
     * - Processes and saves transaction status update received from Payone.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $statusUpdateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer
     */
    public function updateStatus(PayoneTransactionStatusUpdateTransfer $statusUpdateTransfer);

    /**
     * Specification:
     * - Performs GetFile request to Payone API for PDF file download.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $getFileTransfer);

    /**
     * Specification:
     * - Performs GetInvoice request to Payone API for PDF file download.
     * - Sends GenericPayment request to Payone with action "setexpresscheckout" to start express checkout on Paypal side and also to get work order ID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer);

    /**
     * Specification:
     * - Fetches payment details for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer
     */
    public function getPaymentDetail(PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer);

    /**
     * Specification:
     * - Verifies url HMAC signature and fires 'cancel redirect' event.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCancelRedirectTransfer $cancelRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneCancelRedirectTransfer
     */
    public function cancelRedirect(PayoneCancelRedirectTransfer $cancelRedirectTransfer);

    /**
     * Specification:
     * - Performs BankAccountCheck request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer);

    /**
     * Specification:
     * - Performs ManageMandate request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Starts Paypal express checkout to Payone.
     * - Sends GenericPayment request to Payone with action "getexpresscheckoutdetails" in order to get customer data like email and shipping data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer);

    /**
     * Specification:
     * - Retrieves express checkout details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Send request to Payone to get address validation result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressCheckResponseTransfer
     */
    public function sendAddressCheckRequest(QuoteTransfer $quoteTransfer): AddressCheckResponseTransfer;

    /**
     * Specification:
     * - Send request to Payone to get consumer score result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConsumerScoreResponseTransfer
     */
    public function sendConsumerScoreRequest(QuoteTransfer $quoteTransfer): ConsumerScoreResponseTransfer;

    /**
     * Specification:
     * - Sends a request to Payone to start Klarna session.
     * - Returns a client token in case of success, adds an error message otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function sendKlarnaStartSessionRequest(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ): PayoneKlarnaStartSessionResponseTransfer;
}
