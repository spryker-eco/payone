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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneBusinessFactory getFactory()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface getRepository()
 */
class PayoneFacade extends AbstractFacade implements PayoneFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\PayoneFacade::saveOrderPayment} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()->createOrderManager()->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorizePayment(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createPayoneAuthorizeRequestSender()->authorizePayment($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function preAuthorizePayment($idSalesOrder)
    {
        return $this->getFactory()->createPayonePreAuthorizeRequestSender()->preAuthorizePayment($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCaptureTransfer $captureTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function capturePayment(PayoneCaptureTransfer $captureTransfer): CaptureResponseTransfer
    {
        return $this->getFactory()->createPayoneCaptureRequestSender()->capturePayment($captureTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function debitPayment($idPayment)
    {
        return $this->getFactory()->createPayoneDebitRequestSender()->debitPayment($idPayment);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer)
    {
        return $this->getFactory()->createPayoneRefundRequestSender()->refundPayment($refundTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function executePartialRefund(PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer): RefundResponseTransfer
    {
        return $this->getFactory()->createPayonePartialRefundRequestSender()->executePartialRefund($payonePartialOperationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData)
    {
        return $this->getFactory()->createPayoneCreditCardChecker()->creditCardCheck($creditCardData);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer)
    {
        return $this->getFactory()->createPayoneBankAccountChecker()->bankAccountCheck($bankAccountCheckTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(PayoneManageMandateTransfer $manageMandateTransfer)
    {
        return $this->getFactory()->createPayoneManageMandateMethodSender()->manageMandate($manageMandateTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $getFileTransfer)
    {
        return $this->getFactory()->createPayoneFileReader()->getFile($getFileTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer)
    {
        return $this->getFactory()->createPayoneInvoiceReader()->getInvoice($getInvoiceTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): PayoneGetSecurityInvoiceTransfer
    {
        return $this->getFactory()->createPayoneSecurityInvoiceReader()->getSecurityInvoice($getSecurityInvoiceTransfer);
    }

    /**
     * {@inheritDoc}
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
        $transactionTransfer = $this->getFactory()->createTransactionStatusRequest($transactionStatusUpdateTransfer);
        $response = $transactionManager->processTransactionStatusUpdate($transactionTransfer);
        $transactionStatusUpdateTransfer->setIsSuccess($response->isSuccess());
        $transactionStatusUpdateTransfer->setResponse($response->getStatus() . ($response->getErrorMessage() ? ': ' . $response->getErrorMessage() : ''));

        return $transactionStatusUpdateTransfer;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createRefundChecker()->isRefundPossible($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createPaymentDataChecker()->isPaymentDataRequired($orderTransfer);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function orderPostSave(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer
    {
        return $this->getFactory()
            ->createPostSaveHook()
            ->executePostSaveHook($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\PayoneFacade::orderPostSave()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        return $this->getFactory()
            ->createPostSaveHook()
            ->postSaveHook($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\PayoneFacade::orderPostSave()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function executeCheckoutPostSaveHook(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponse
    ): CheckoutResponseTransfer {
        return $this->getFactory()
            ->createPostSaveHook()
            ->executeCheckoutPostSaveHook($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
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

        return $this->getRepository()->getPaymentLogs($orders);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail($idOrder)
    {
        return $this->getRepository()->getPaymentDetail($idOrder);
    }

    /**
     * {@inheritDoc}
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
        $this->getEntityManager()->updatePaymentDetail($paymentData, $idOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer)
    {
        return $this->getFactory()->createPayoneInitPaypalExpressCheckoutMethodSender()->initPaypalExpressCheckout($requestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createPayonePaypalExpressCheckoutDetailsReader()->getPaypalExpressCheckoutDetails($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressCheckResponseTransfer
     */
    public function sendAddressCheckRequest(QuoteTransfer $quoteTransfer): AddressCheckResponseTransfer
    {
        return $this->getFactory()
            ->createRiskCheckManager()
            ->sendAddressCheckRequest($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConsumerScoreResponseTransfer
     */
    public function sendConsumerScoreRequest(QuoteTransfer $quoteTransfer): ConsumerScoreResponseTransfer
    {
        return $this->getFactory()
            ->createRiskCheckManager()
            ->sendConsumerScoreRequest($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        return $this->getFactory()
            ->createPaymentMethodFilter()
            ->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return string|null
     */
    public function findPayoneOrderItemStatus(int $idSalesOrder, int $idSalesOrderItem): ?string
    {
        return $this->getFactory()
            ->createPayoneOrderItemStatusFinder()
            ->findPayoneOrderItemStatus($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function executePartialCapture(
        PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
    ): CaptureResponseTransfer {
        return $this->getFactory()
            ->createPayonePartialCaptureRequestSender()
            ->executePartialCapture($payonePartialOperationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function sendKlarnaStartSessionRequest(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ): PayoneKlarnaStartSessionResponseTransfer {
        return $this->getFactory()
            ->createPayoneKlarnaStartSessionMethodSender()
            ->sendKlarnaStartSessionRequest($payoneKlarnaStartSessionRequestTransfer);
    }
}
