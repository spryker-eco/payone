<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business;

use Generated\Shared\Transfer\AddressCheckResponseTransfer;
use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ConsumerScoreResponseTransfer;
use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCaptureTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneBusinessFactory getFactory()
 */
interface PayoneFacadeInterface
{
    /**
     * Specification:
     * - Requires `CheckoutResponseTransfer.SaveOrder` to be set.
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface::saveOrderPayment()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Saves order payment method data according to quote and order data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Performs payment authorization request to Payone API and updates payment data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorizePayment(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Performs payment pre-authorization request to Payone API and updates payment data.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function preAuthorizePayment($idSalesOrder);

    /**
     * Specification:
     * - Requires `PayoneCaptureTransfer.order` to be set.
     * - Requires `PayoneCaptureTransfer.payment.fkSalesOrder` to be set.
     * - Distributes the prices for PayoneCaptureTransfer.order items and expenses depending on the payment method.
     * - Performs payment capture request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCaptureTransfer $captureTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function capturePayment(PayoneCaptureTransfer $captureTransfer): CaptureResponseTransfer;

    /**
     * Specification:
     * - Performs payment debit request to Payone API.
     * - Throws `PaymentNotFoundException` if SpyPaymentPayone not found.
     * - Throws `TransactionMissingException` if SpyPaymentPayone.transactionId is missing.
     *
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function debitPayment($idPayment);

    /**
     * Specification:
     * - Requires `PayoneRefundTransfer.order` to be set.
     * - Requires `PayoneRefundTransfer.payment.fkSalesOrder` to be set.
     * - Distributes the prices for PayoneRefundTransfer.order items and expenses depending on the payment method.
     * - Performs payment refund request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer);

    /**
     * Specification:
     * - Requires `PayonePartialOperationRequestTransfer.order.idSalesOrder` to be set.
     * - Performs partial refund request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function executePartialRefund(PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer): RefundResponseTransfer;

    /**
     * Specification:
     * - Requires `PayoneCreditCardTransfer.payment.paymentMethod`.
     * - Performs credit card check request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData);

    /**
     * Specification:
     * - Processes and saves transaction status update request received from Payone.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer
     */
    public function processTransactionStatusUpdate(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'APPROVED' response to authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'REDIRECT' response to authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'APPROVED' response to pre-authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationApproved(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'REDIRECT' response to pre-authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationRedirect(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'ERROR' response for pre-authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationError(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'ERROR' response to authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'APPROVED' response to capture request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'ERROR' response to capture request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'APPROVED' response to refund request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if API logs contain 'ERROR' response to refund request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if refund is possible for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Checks if used payment method references stored iban/bic data for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Checks if there are unprocessed transaction status logs for given order/item.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Checks if first unprocessed transaction status log record for given order/item has 'PAID' status with zero or negative balance.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentPaid($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Checks if first unprocessed transaction status log record for given order/item has 'PAID' status with negative balance.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOverpaid($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Checks if first unprocessed transaction status log record for given order/item has 'UNDERPAID' status.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Checks if first unprocessed transaction status log record for given order/item has 'REFUND' status.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentRefund($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Checks if first unprocessed transaction status log record for given order/item has 'APPOINTED' status.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentAppointed($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Checks if first unprocessed transaction status log record for given order/item is not in 'PAID', 'APPOINTED' or 'UNDERPAID' status.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOther($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Checks if first unprocessed transaction status log record for given order/item has 'CAPTURE' status.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentCapture($idSalesOrder, $idSalesOrderItem);

    /**
     * Specification:
     * - Requires `CheckoutResponseTransfer.saveOrder.idSalesOrder` to be set.
     * - Requires `QuoteTransfer.payment.payone.fkSalesOrder` to be set.
     * - Handles redirects and errors after order placement.
     * - Executes `authorization` or `pre-authorization` API call depends on payment method.
     * - Updates `CheckoutResponseTransfer` with errors or/and redirect url accordingly to API response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function orderPostSave(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.payment.payone.fkSalesOrder` to be set.
     * - Handles redirects and errors after order placement.
     *
     * @api
     *
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface::orderPostSave()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Requires `CheckoutResponseTransfer.saveOrder.idSalesOrder` to be set.
     * - Executes `authorization` or `pre-authorization` API call depends on payment method.
     * - Updates `CheckoutResponseTransfer` with errors or/and redirect url accordingly to API response.
     *
     * @api
     *
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface::orderPostSave()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function executeCheckoutPostSaveHook(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponse
    ): CheckoutResponseTransfer;

    /**
     * Specification:
     * - Gets payment logs (both api and transaction status) for specific orders in chronological order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderCollectionTransfer $orderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs(OrderCollectionTransfer $orderCollectionTransfer);

    /**
     * Specification:
     * - Gets payment details for given order.
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail($idOrder);

    /**
     * Specification:
     * - Updates payment details for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentDetailTransfer $paymentData
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDetailTransfer $paymentData, $idOrder);

    /**
     * Specification:
     * - Performs bank account check request to Payone API.
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
     * - Performs manage mandate request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(PayoneManageMandateTransfer $manageMandateTransfer);

    /**
     * Specification:
     * - Requires `PayoneGetFileTransfer.reference` and `PayoneGetFileTransfer.customerId` to be set.
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
     * - Requires `PayoneGetInvoiceTransfer.reference` and `PayoneGetInvoiceTransfer.customerId` to be set.
     * - Performs GetInvoice request to Payone API for PDF file download.
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
     * - Requires `PayoneGetSecurityInvoiceTransfer.reference` and `PayoneGetSecurityInvoiceTransfer.customerId` to be set.
     * - Performs GetSecurityInvoice request to Payone API for PDF file download.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoice(
        PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
    ): PayoneGetSecurityInvoiceTransfer;

    /**
     * Specification:
     * - Requires `PayoneInitPaypalExpressCheckoutRequestTransfer.quote` to be set.
     * - Performs init express checkout request to Payone API.
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
     * - Sends request to Payone to get address check result based on customer billing address.
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
     * - Sends request to Payone to get consumer score result based on customer billing info.
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
     * - Filters Payone's payment methods based on consumer score result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer): PaymentMethodsTransfer;

    /**
     * Specification:
     * - Finds Payone order item status.
     * - Returns null in case payone order item was not found.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return string|null
     */
    public function findPayoneOrderItemStatus(int $idSalesOrder, int $idSalesOrderItem): ?string;

    /**
     * Specification:
     * - Requires `PayonePartialOperationRequestTransfer.order.idSalesOrder` to be set.
     * - Distributes the prices for PayonePartialOperationRequestTransfer.order items and expenses depending on the payment method.
     * - Performs partial capture call to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function executePartialCapture(
        PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
    ): CaptureResponseTransfer;

    /**
     * Specification:
     * - Requires `PayoneKlarnaStartSessionRequestTransfer.quote` to be set.
     * - Starts Payone Klarna session.
     * - Returns client token in case session started successfully.
     * - Returns 'isSuccessful=false' and error message in case of error.
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
