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
        /** @var \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $payoneTransactionStatusUpdateTransfer */
        $payoneTransactionStatusUpdateTransfer = $this->zedStub->call(
            '/payone/gateway/status-update',
            $transactionStatus,
        );

        return $payoneTransactionStatusUpdateTransfer;
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
        /** @var \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $payoneBankAccountCheckTransfer */
        $payoneBankAccountCheckTransfer = $this->zedStub->call(
            '/payone/gateway/bank-account-check',
            $bankAccountCheckTransfer,
        );

        return $payoneBankAccountCheckTransfer;
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
        /** @var \Generated\Shared\Transfer\PayoneManageMandateTransfer $payoneManageMandateTransfer */
        $payoneManageMandateTransfer = $this->zedStub->call(
            '/payone/gateway/manage-mandate',
            $manageMandateTransfer,
        );

        return $payoneManageMandateTransfer;
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
        /** @var \Generated\Shared\Transfer\PayoneGetFileTransfer $payoneGetFileTransfer */
        $payoneGetFileTransfer = $this->zedStub->call(
            '/payone/gateway/get-file',
            $getFileTransfer,
        );

        return $payoneGetFileTransfer;
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
        /** @var \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer $payoneGetPaymentDetailTransfer */
        $payoneGetPaymentDetailTransfer = $this->zedStub->call(
            '/payone/gateway/get-payment-detail',
            $getPaymentDetailTransfer,
        );

        return $payoneGetPaymentDetailTransfer;
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
        /** @var \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $payoneGetInvoiceTransfer */
        $payoneGetInvoiceTransfer = $this->zedStub->call(
            '/payone/gateway/get-invoice',
            $getInvoiceTransfer,
        );

        return $payoneGetInvoiceTransfer;
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
        /** @var \Generated\Shared\Transfer\PayoneCancelRedirectTransfer $payoneCancelRedirectTransfer */
        $payoneCancelRedirectTransfer = $this->zedStub->call(
            '/payone/gateway/cancel-redirect',
            $cancelRedirectTransfer,
        );

        return $payoneCancelRedirectTransfer;
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
        /** @var \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $payonePaypalExpressCheckoutGenericPaymentResponseTransfer */
        $payonePaypalExpressCheckoutGenericPaymentResponseTransfer = $this->zedStub->call(
            '/payone/gateway/init-paypal-express-checkout',
            $requestTransfer,
        );

        return $payonePaypalExpressCheckoutGenericPaymentResponseTransfer;
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
        /** @var \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $payonePaypalExpressCheckoutGenericPaymentResponseTransfer */
        $payonePaypalExpressCheckoutGenericPaymentResponseTransfer = $this->zedStub->call(
            '/payone/gateway/get-paypal-express-checkout-details',
            $quoteTransfer,
        );

        return $payonePaypalExpressCheckoutGenericPaymentResponseTransfer;
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
        /** @var \Generated\Shared\Transfer\AddressCheckResponseTransfer $addressCheckResponseTransfer */
        $addressCheckResponseTransfer = $this->zedStub->call(
            '/payone/gateway/send-address-check-request',
            $quoteTransfer,
        );

        return $addressCheckResponseTransfer;
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
        /** @var \Generated\Shared\Transfer\ConsumerScoreResponseTransfer $consumerScoreResponseTransfer */
        $consumerScoreResponseTransfer = $this->zedStub->call(
            '/payone/gateway/send-consumer-score-request',
            $quoteTransfer,
        );

        return $consumerScoreResponseTransfer;
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
        /** @var \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer $payoneKlarnaStartSessionResponseTransfer */
        $payoneKlarnaStartSessionResponseTransfer = $this->zedStub->call(
            '/payone/gateway/send-klarna-start-session-request',
            $payoneKlarnaStartSessionRequestTransfer,
        );

        return $payoneKlarnaStartSessionResponseTransfer;
    }
}
