<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCaptureTransfer;
use Generated\Shared\Transfer\PayoneCreditCardCheckRequestDataTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;

interface PaymentManagerInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface $paymentMethodMapper
     *
     * @return void
     */
    public function registerPaymentMethodMapper(PaymentMethodMapperInterface $paymentMethodMapper);

    /**
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer);

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function executePartialRefund(PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer): RefundResponseTransfer;

    /**
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function debitPayment($idPayment);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorizePayment(OrderTransfer $orderTransfer);

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function preAuthorizePayment($idSalesOrder);

    /**
     * @param \Generated\Shared\Transfer\PayoneCaptureTransfer $captureTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function capturePayment(PayoneCaptureTransfer $captureTransfer);

    /**
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData);

    /**
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer);

    /**
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(PayoneManageMandateTransfer $manageMandateTransfer);

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $getFileTransfer);

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer);

    /**
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\ArrayObject $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs($orders);

    /**
     * @param \Generated\Shared\Transfer\PayoneCreditCardCheckRequestDataTransfer $creditCardCheckRequestDataTransfer
     *
     * @return array
     */
    public function getCreditCardCheckRequestData(PayoneCreditCardCheckRequestDataTransfer $creditCardCheckRequestDataTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail($idOrder);

    /**
     * @param \Generated\Shared\Transfer\PaymentDetailTransfer $paymentDataTransfer
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDetailTransfer $paymentDataTransfer, $idOrder);

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function executePartialCapture(
        PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
    ): CaptureResponseTransfer;
}
