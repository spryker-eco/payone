<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DummyAdapter;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class PayoneFacadeExecutePartialRefundTest extends AbstractBusinessTest
{
    protected const FAKE_REFUND_RESPONSE = '{"txid":"375461930","status":"APPROVED"}';
    protected const ORDER_ITEM_STATUS_REFUND_APPROVED = 'refund approved';

    /**
     * @var \SprykerEcoTest\Zed\Payone\PayoneZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([PayoneZedTester::TEST_STATE_MACHINE_NAME]);
    }

    /**
     * @return void
     */
    public function testExecutePartialRefund(): void
    {
        $saveOrderTransfer = $this->tester->createOrder();
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);
        $paymentPayoneEntity = $this->tester->createPaymentPayone($saveOrderTransfer->getIdSalesOrder());
        $this->tester->createPaymentPayoneOrderItem($paymentPayoneEntity->getIdPaymentPayone(), $itemTransfer->getIdSalesOrderItem());
        $refundTransfer = $this->tester->createRefund($saveOrderTransfer->getIdSalesOrder());
        $orderTransfer = $this->tester->getOrderTransfer($saveOrderTransfer->getIdSalesOrder());

        $payonePartialOperationTransfer = (new PayonePartialOperationRequestTransfer())
            ->setOrder($orderTransfer)
            ->setRefund($refundTransfer)
            ->addSalesOrderItemId($itemTransfer->getIdSalesOrderItem());

        //Act
        $refundResponseTransfer = $this->createFacadeMock(
            new DummyAdapter(static::FAKE_REFUND_RESPONSE)
        )->executePartialRefund($payonePartialOperationTransfer);
        $status = $this->tester->getFacade()->findPayoneOrderItemStatus($saveOrderTransfer->getIdSalesOrder(), $itemTransfer->getIdSalesOrderItem());

        //Assert
        $this->assertInstanceOf(RefundResponseTransfer::class, $refundResponseTransfer);
        $this->assertSame('375461930', $refundResponseTransfer->getTxid());
        $this->assertSame(static::ORDER_ITEM_STATUS_REFUND_APPROVED, $status);
    }

    /**
     * @return \Codeception\Module
     */
    protected function getConfigHelper()
    {
        return $this->getModule('\\' . ConfigHelper::class);
    }

    /**
     * @return void
     */
    protected function setupConfig()
    {
        $this->getConfigHelper()->setConfig(
            PayoneConstants::PAYONE,
            [
                PayoneConstants::PAYONE_CREDENTIALS_ENCODING => 'UTF-8',
                PayoneConstants::PAYONE_CREDENTIALS_KEY => '',
                PayoneConstants::PAYONE_CREDENTIALS_MID => '',
                PayoneConstants::PAYONE_CREDENTIALS_AID => '',
                PayoneConstants::PAYONE_CREDENTIALS_PORTAL_ID => '',
                PayoneConstants::PAYONE_PAYMENT_GATEWAY_URL => 'https://api.pay1.de/post-gateway/',
                PayoneConstants::PAYONE_REDIRECT_SUCCESS_URL => '/checkout/success',
                PayoneConstants::PAYONE_REDIRECT_ERROR_URL => '/checkout/payment',
                PayoneConstants::PAYONE_REDIRECT_BACK_URL => '/payone/regular-redirect-payment-cancellation',
                PayoneConstants::PAYONE_MODE => 'test',
                PayoneConstants::PAYONE_EMPTY_SEQUENCE_NUMBER => 0,
                PayoneConstants::PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL => '',
                PayoneConstants::PAYONE_EXPRESS_CHECKOUT_FAILURE_URL => '',
                PayoneConstants::PAYONE_EXPRESS_CHECKOUT_BACK_URL => '',
                PayoneConstants::PAYONE_ADDRESS_CHECK_TYPE => 'BA',
                PayoneConstants::PAYONE_CONSUMER_SCORE_TYPE => 'IH',
                PayoneConstants::PAYONE_GREEN_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_INVOICE,
                    PayoneConfig::PAYMENT_METHOD_CREDIT_CARD,
                ],
                PayoneConstants::PAYONE_YELLOW_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER,
                ],
                PayoneConstants::PAYONE_RED_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT,
                ],
                PayoneConstants::PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_CREDIT_CARD,
                    PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER,
                    PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT,
                ],
            ]
        );
    }
}
