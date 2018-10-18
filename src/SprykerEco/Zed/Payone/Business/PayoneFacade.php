<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business;

use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCaptureTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneBusinessFactory getFactory()
 */
class PayoneFacade extends AbstractFacade implements PayoneFacadeInterface
{
    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createOrderManager()->saveOrder($quoteTransfer, $checkoutResponse);
    }

    /**
     * Specification:
     * - Performs payment authorization request to Payone API and updates payment data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorizePayment(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createPaymentManager()->authorizePayment($orderTransfer);
    }

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
    public function preAuthorizePayment($idSalesOrder)
    {
        return $this->getFactory()->createPaymentManager()->preAuthorizePayment($idSalesOrder);
    }

    /**
     * Specification:
     * - Performs payment capture request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCaptureTransfer $captureTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function capturePayment(PayoneCaptureTransfer $captureTransfer): CaptureResponseTransfer
    {
        return $this->getFactory()->createPaymentManager()->capturePayment($captureTransfer);
    }

    /**
     * Specification:
     * - Performs payment debit request to Payone API.
     *
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function debitPayment($idPayment)
    {
        return $this->getFactory()->createPaymentManager()->debitPayment($idPayment);
    }

    /**
     * Specification:
     * - Performs payment refund request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer)
    {
        return $this->getFactory()->createPaymentManager()->refundPayment($refundTransfer);
    }

    /**
     * Specification:
     * - Performs credit card check request to Payone API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData)
    {
        return $this->getFactory()->createPaymentManager()->creditCardCheck($creditCardData);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer)
    {
        return $this->getFactory()->createPaymentManager()->bankAccountCheck($bankAccountCheckTransfer);
    }

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
    public function manageMandate(PayoneManageMandateTransfer $manageMandateTransfer)
    {
        return $this->getFactory()->createPaymentManager()->manageMandate($manageMandateTransfer);
    }

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
    public function getFile(PayoneGetFileTransfer $getFileTransfer)
    {
        return $this->getFactory()->createPaymentManager()->getFile($getFileTransfer);
    }

    /**
     * Specification:
     * - Performs GetInvoice request to Payone API for PDF file download.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer)
    {
        return $this->getFactory()->createPaymentManager()->getInvoice($getInvoiceTransfer);
    }

    /**
     * Specification:
     * - Performs GetInvoice request to Payone API for PDF file download.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): PayoneGetSecurityInvoiceTransfer
    {
        return $this->getFactory()->createPaymentManager()->getSecurityInvoice($getSecurityInvoiceTransfer);
    }

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
    public function processTransactionStatusUpdate(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer)
    {
        $transactionManager = $this->getFactory()->createTransactionStatusManager();
        $transactionTransfer = $this->getFactory()->createTransactionStatusUpdateRequest($transactionStatusUpdateTransfer);
        $response = $transactionManager->processTransactionStatusUpdate($transactionTransfer);
        $transactionStatusUpdateTransfer->setIsSuccess($response->isSuccess());
        $transactionStatusUpdateTransfer->setResponse($response->getStatus() . ($response->getErrorMessage() ? ': ' . $response->getErrorMessage() : ''));

        return $transactionStatusUpdateTransfer;
    }

    /**
     * Specification:
     * - Checks if API logs contain 'APPROVED' response for authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isAuthorizationApproved($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'REDIRECT' response for authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isAuthorizationRedirect($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'APPROVED' response for pre-authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'REDIRECT' response for pre-authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isPreAuthorizationRedirect($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'ERROR' response for pre-authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationError(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isPreAuthorizationError($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'ERROR' response for authorization request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isAuthorizationError($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'APPROVED' response to capture request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isCaptureApproved($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'ERROR' response to capture request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isCaptureError($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'APPROVED' response to refund request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isRefundApproved($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if API logs contain 'ERROR' response to refund request for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isRefundError($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if refund is possible for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createPaymentManager()->isRefundPossible($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if used payment method references stored iban/bic data for given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createPaymentManager()->isPaymentDataRequired($orderTransfer);
    }

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
    public function isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem);
    }

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
    public function isPaymentPaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentPaid($idSalesOrder, $idSalesOrderItem);
    }

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
    public function isPaymentOverpaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentOverpaid($idSalesOrder, $idSalesOrderItem);
    }

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
    public function isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem);
    }

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
    public function isPaymentRefund($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentRefund($idSalesOrder, $idSalesOrderItem);
    }

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
    public function isPaymentAppointed($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentAppointed($idSalesOrder, $idSalesOrderItem);
    }

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
    public function isPaymentOther($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentOther($idSalesOrder, $idSalesOrderItem);
    }

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
    public function isPaymentCapture($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentCapture($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * Specification:
     * - Handles redirects and errors after order placement.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        return $this->getFactory()
            ->createPaymentManager()
            ->postSaveHook($quoteTransfer, $checkoutResponse);
    }

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
    public function getPaymentLogs(OrderCollectionTransfer $orderCollectionTransfer)
    {
        $orders = $orderCollectionTransfer->getOrders();
        return $this->getFactory()->createPaymentManager()->getPaymentLogs($orders);
    }

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
    public function getPaymentDetail($idOrder)
    {
        return $this->getFactory()->createPaymentManager()->getPaymentDetail($idOrder);
    }

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
    public function updatePaymentDetail(PaymentDetailTransfer $paymentData, $idOrder)
    {
        $this->getFactory()->createPaymentManager()->updatePaymentDetail($paymentData, $idOrder);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer)
    {
        return $this->getFactory()->createPaymentManager()->initPaypalExpressCheckout($requestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createPaymentManager()->getPaypalExpressCheckoutDetails($quoteTransfer);
    }
}
