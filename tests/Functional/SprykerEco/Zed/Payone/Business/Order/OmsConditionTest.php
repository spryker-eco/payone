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

    public function testIsAuthorizationApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isAuthorizationApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }
    

    public function testIsAuthorizationRedirect()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_REDIRECT);
        $isRedirect = $this->payoneFacade->isAuthorizationRedirect($this->orderTransfer);

        $this->assertTrue($isRedirect);
    }

    public function testIsAuthorizationError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isAuthorizationError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    public function testIsPreAuthorizationApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isPreauthorizationApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }

    public function testIsPreauthorizationRedirect()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_REDIRECT);
        $isRedirect = $this->payoneFacade->isPreauthorizationRedirect($this->orderTransfer);

        $this->assertTrue($isRedirect);
    }

    public function testIsPreAuthorizationError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isPreAuthorizationError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    public function testIsPreAuthorizationErrorTimeout()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_TIMEOUT);
        $isError = $this->payoneFacade->isPreAuthorizationError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    public function testIsCaptureApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_CAPTURE, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isCaptureApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }

    /**
     */
    public function testIsCaptureError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_CAPTURE, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isCaptureError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    public function testIsRefundApproved()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $isApproved = $this->payoneFacade->isRefundApproved($this->orderTransfer);

        $this->assertTrue($isApproved);
    }

    public function testIsRefundError()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $isError = $this->payoneFacade->isRefundError($this->orderTransfer);

        $this->assertTrue($isError);
    }

    public function testIsRefundPossible()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail('iban', 'bic');
        $isPossible = $this->payoneFacade->isRefundPossible($this->orderTransfer);

        $this->assertTrue($isPossible);
    }

    public function testIsRefundImpossible()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();
        $isPossible = $this->payoneFacade->isRefundPossible($this->orderTransfer);

        $this->assertFalse($isPossible);
    }

    public function testIsPaymentDataRequiredInvoice()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();
        $isRequired = $this->payoneFacade->isPaymentDataRequired($this->orderTransfer);

        $this->assertTrue($isRequired);
    }

    public function testIsPaymentDataRequiredEWallet()
    {
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_E_WALLET);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();
        $isRequired = $this->payoneFacade->isPaymentDataRequired($this->orderTransfer);

        $this->assertFalse($isRequired);
    }

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
     */
    public function testIsPaymentNotificationAvailable()
    {
        /////////
    }

    public function testIsPaymentPaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID);
        $isPaid = $this->payoneFacade->isPaymentPaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isPaid);
    }

    public function testIsNotPaymentPaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID, 999);
        $isPaid = $this->payoneFacade->isPaymentPaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isPaid);
    }

    public function testIsPaymentOverpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID, -100);
        $isOverPaid = $this->payoneFacade->isPaymentOverpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isOverPaid);
    }

    public function testIsPaymentNotOverpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_PAID);
        $isOverPaid = $this->payoneFacade->isPaymentOverpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isOverPaid);
    }

    public function testIsPaymentUnderpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_UNDERPAID);
        $isUnderPaid = $this->payoneFacade->isPaymentUnderpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isUnderPaid);
    }

    public function testIsPaymentNotUnderpaid()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_UNDERPAID);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isUnderPaid = $this->payoneFacade->isPaymentUnderpaid($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isUnderPaid);
    }

    public function testIsPaymentRefund()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_REFUND);
        $isRefund = $this->payoneFacade->isPaymentRefund($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isRefund);
    }

    public function testIsPaymentNotRefund()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_REFUND);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isRefund = $this->payoneFacade->isPaymentRefund($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isRefund);
    }

    public function testIsPaymentAppointed()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_APPOINTED);
        $isAppointed = $this->payoneFacade->isPaymentAppointed($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isAppointed);
    }

    public function testIsPaymentNotAppointed()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_APPOINTED);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isAppointed = $this->payoneFacade->isPaymentAppointed($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isAppointed);
    }

    public function testIsPaymentCapture()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_CAPTURE);
        $isCapture = $this->payoneFacade->isPaymentCapture($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isCapture);
    }

    public function testIsPaymentNotCapture()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_CAPTURE);
        $this->createPayoneTransactionStatusLogItem($idOrderItem);
        $isCapture = $this->payoneFacade->isPaymentCapture($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isCapture);
    }

    public function testIsPaymentOther()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_DEBIT);
        $isOther = $this->payoneFacade->isPaymentOther($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertTrue($isOther);
    }

    public function testIsPaymentNotOther()
    {
        $idOrderItem = $this->orderEntity->getItems()->getFirst()->getIdSalesOrderItem();
        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneTransactionStatusLog(PayoneTransactionStatusConstants::TXACTION_APPOINTED);
        $isOther = $this->payoneFacade->isPaymentOther($this->orderEntity->getIdSalesOrder(), $idOrderItem);

        $this->assertFalse($isOther);
    }

}