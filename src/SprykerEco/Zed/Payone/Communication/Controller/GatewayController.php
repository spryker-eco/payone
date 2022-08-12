<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Controller;

use Generated\Shared\Transfer\AddressCheckResponseTransfer;
use Generated\Shared\Transfer\ConsumerScoreResponseTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCancelRedirectTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerEco\Shared\Payone\PayoneConstants;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer
     */
    public function statusUpdateAction(
        PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
    ) {
        $transactionStatusUpdateTransfer = $this
            ->getFacade()
            ->processTransactionStatusUpdate($transactionStatusUpdateTransfer);

        $this->triggerEventsOnSuccess($transactionStatusUpdateTransfer);

        return $transactionStatusUpdateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneCancelRedirectTransfer $cancelRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneCancelRedirectTransfer
     */
    public function cancelRedirectAction(PayoneCancelRedirectTransfer $cancelRedirectTransfer)
    {
        $urlHmacGenerator = $this->getFactory()->createUrlHmacGenerator();
        $hash = $urlHmacGenerator->hash(
            $cancelRedirectTransfer->getOrderReferenceOrFail(),
            $this->getFactory()->getConfig()->getRequestStandardParameter()->getKeyOrFail(),
        );

        if ($cancelRedirectTransfer->getUrlHmac() === $hash) {
            $orderItems = SpySalesOrderItemQuery::create()
                ->useOrderQuery()
                    ->filterByOrderReference($cancelRedirectTransfer->getOrderReference())
                ->endUse()
                ->find();

            $this->getFactory()->getOmsFacade()->triggerEvent('cancel redirect', $orderItems, []);
        }

        return $cancelRedirectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckoutAction(
        PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
    ) {
        return $this->getFacade()->initPaypalExpressCheckout($requestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetailsAction(QuoteTransfer $quoteTransfer) {
        return $this->getFacade()->getPaypalExpressCheckoutDetails($quoteTransfer);
    }

    /**
     * @internal param TransactionStatusResponse $response
     *
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return void
     */
    protected function triggerEventsOnSuccess(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer)
    {
        if (!$transactionStatusUpdateTransfer->getIsSuccess()) {
            return;
        }

        $orderItems = SpySalesOrderItemQuery::create()
            ->useOrderQuery()
            ->useSpyPaymentPayoneQuery()
                ->filterByTransactionId($transactionStatusUpdateTransfer->getTxid())
            ->endUse()
            ->endUse()
            ->find();
        $this->getFactory()->getOmsFacade()->triggerEvent('PaymentNotificationReceived', $orderItems, []);

        if ($transactionStatusUpdateTransfer->getTxaction() === PayoneConstants::PAYONE_TXACTION_APPOINTED) {
            $this->getFactory()->getOmsFacade()->triggerEvent('RedirectResponseAppointed', $orderItems, []);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheck
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheckAction(PayoneBankAccountCheckTransfer $bankAccountCheck)
    {
        return $this->getFacade()->bankAccountCheck($bankAccountCheck);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandateAction(PayoneManageMandateTransfer $manageMandateTransfer)
    {
        return $this->getFacade()->manageMandate($manageMandateTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFileAction(PayoneGetFileTransfer $getFileTransfer)
    {
        return $this->getFacade()->getFile($getFileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoiceAction(PayoneGetInvoiceTransfer $getInvoiceTransfer)
    {
        return $this->getFacade()->getInvoice($getInvoiceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoiceAction(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): PayoneGetSecurityInvoiceTransfer
    {
        return $this->getFacade()->getSecurityInvoice($getSecurityInvoiceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer
     */
    public function getPaymentDetailAction(PayoneGetPaymentDetailTransfer $getPaymentDetailTransfer)
    {
        if (!empty($getPaymentDetailTransfer->getOrderReference())) {
            $order = SpySalesOrderQuery::create()
                ->filterByOrderReference($getPaymentDetailTransfer->getOrderReference())
                ->findOne();
            $getPaymentDetailTransfer->setOrderId((string)$order->getIdSalesOrder());
        }
        $response = $this->getFacade()->getPaymentDetail((int)$getPaymentDetailTransfer->getOrderIdOrFail());
        $getPaymentDetailTransfer->setPaymentDetail($response);

        return $getPaymentDetailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressCheckResponseTransfer
     */
    public function sendAddressCheckRequestAction(QuoteTransfer $quoteTransfer): AddressCheckResponseTransfer
    {
        return $this->getFacade()->sendAddressCheckRequest($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConsumerScoreResponseTransfer
     */
    public function sendConsumerScoreRequestAction(QuoteTransfer $quoteTransfer): ConsumerScoreResponseTransfer
    {
        return $this->getFacade()->sendConsumerScoreRequest($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function sendKlarnaStartSessionRequestAction(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ): PayoneKlarnaStartSessionResponseTransfer {
        return $this->getFacade()->sendKlarnaStartSessionRequest($payoneKlarnaStartSessionRequestTransfer);
    }
}
