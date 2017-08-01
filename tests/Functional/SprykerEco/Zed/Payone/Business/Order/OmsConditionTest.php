<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business\Order;

use Functional\SprykerEco\Zed\Payone\Business\AbstractPayoneTest;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneTransactionStatusConstants;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Order
 * @group OmsConditionTest
 */
class OmsConditionTest extends AbstractPayoneTest
{

    /**
     * @return void
     */
    public function testIsAuthorizationApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isAuthorizationApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }

    /**
     * @return void
     */
    public function testIsAuthorizationRedirect()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_REDIRECT);
        $isRedirect = $this->payoneFacade->isAuthorizationRedirect($this->orderTransfer);

        $this->assertTrue($isRedirect);
    }

    /**
     * @return void
     */
    public function testIsAuthorizationError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isAuthorizationError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    /**
     * @return void
     */
    public function testIsPreAuthorizationApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isPreauthorizationApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }

    /**
     * @return void
     */
    public function testIsPreauthorizationRedirect()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_REDIRECT);
        $isRedirect = $this->payoneFacade->isPreauthorizationRedirect($this->orderTransfer);

        $this->assertTrue($isRedirect);
    }

    /**
     * @return void
     */
    public function testIsPreAuthorizationError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isPreAuthorizationError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    /**
     * @return void
     */
    public function testIsPreAuthorizationErrorTimeout()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_TIMEOUT);
        $isError = $this->payoneFacade->isPreAuthorizationError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    /**
     * @return void
     */
    public function testIsCaptureApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_CAPTURE, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isCaptureApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }

    /**
     * @return void
     */
    public function testIsCaptureError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_CAPTURE, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isCaptureError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    /**
     * @return void
     */
    public function testIsRefundApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isRefundApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }

    /**
     * @return void
     */
    public function testIsRefundError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isRefundError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    /**
     * @return void
     */
    public function testIsRefundPossible()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail('iban', 'bic');
        $isPossible = $this->payoneFacade->isRefundPossible($this->orderTransfer);

        $this->assertTrue($isPossible);
    }

    /**
     * @return void
     */
    public function testIsRefundImpossible()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();
        $isPossible = $this->payoneFacade->isRefundPossible($this->orderTransfer);

        $this->assertFalse($isPossible);
    }

    /**
     * @return void
     */
    public function testIsPaymentDataRequiredInvoice()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();
        $isRequired = $this->payoneFacade->isPaymentDataRequired($this->orderTransfer);

        $this->assertTrue($isRequired);
    }

    /**
     * @return void
     */
    public function testIsPaymentDataRequiredEWallet()
    {
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_E_WALLET);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();
        $isRequired = $this->payoneFacade->isPaymentDataRequired($this->orderTransfer);

        $this->assertFalse($isRequired);
    }

    /**
     * @return void
     */
    public function testIsPaymentDataRequiredCreditCardPseudo()
    {
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();
        $isRequired = $this->payoneFacade->isPaymentDataRequired($this->orderTransfer);

        $this->assertFalse($isRequired);
    }

    /**
     * @ignore Broken implementation.
     *
     * @return void
     */
    public function testIsPaymentNotificationAvailable()
    {
        /////////
    }

    /**
     * @return void
     */
    public function testIsPaymentPaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID);
        $isPaid = $this->payoneFacade->isPaymentPaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isPaid);
    }

    /**
     * @return void
     */
    public function testIsNotPaymentPaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID, 999);
        $isPaid = $this->payoneFacade->isPaymentPaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isPaid);
    }

    /**
     * @return void
     */
    public function testIsPaymentOverpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID, -100);
        $isOverPaid = $this->payoneFacade->isPaymentOverpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isOverPaid);
    }

    /**
     * @return void
     */
    public function testIsPaymentNotOverpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID);
        $isOverPaid = $this->payoneFacade->isPaymentOverpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isOverPaid);
    }

    /**
     * @return void
     */
    public function testIsPaymentUnderpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_UNDERPAID);
        $isUnderPaid = $this->payoneFacade->isPaymentUnderpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isUnderPaid);
    }

    /**
     * @return void
     */
    public function testIsPaymentNotUnderpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_UNDERPAID);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isUnderPaid = $this->payoneFacade->isPaymentUnderpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isUnderPaid);
    }

    /**
     * @return void
     */
    public function testIsPaymentRefund()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_REFUND);
        $isRefund = $this->payoneFacade->isPaymentRefund($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isRefund);
    }

    /**
     * @return void
     */
    public function testIsPaymentNotRefund()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_REFUND);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isRefund = $this->payoneFacade->isPaymentRefund($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isRefund);
    }

    /**
     * @return void
     */
    public function testIsPaymentAppointed()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_APPOINTED);
        $isAppointed = $this->payoneFacade->isPaymentAppointed($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isAppointed);
    }

    /**
     * @return void
     */
    public function testIsPaymentNotAppointed()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_APPOINTED);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isAppointed = $this->payoneFacade->isPaymentAppointed($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isAppointed);
    }

    /**
     * @return void
     */
    public function testIsPaymentCapture()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_CAPTURE);
        $isCapture = $this->payoneFacade->isPaymentCapture($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isCapture);
    }

    /**
     * @return void
     */
    public function testIsPaymentNotCapture()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_CAPTURE);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isCapture = $this->payoneFacade->isPaymentCapture($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isCapture);
    }

    /**
     * @return void
     */
    public function testIsPaymentOther()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_DEBIT);
        $isOther = $this->payoneFacade->isPaymentOther($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isOther);
    }

    /**
     * @return void
     */
    public function testIsPaymentNotOther()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_APPOINTED);
        $isOther = $this->payoneFacade->isPaymentOther($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isOther);
    }

}
