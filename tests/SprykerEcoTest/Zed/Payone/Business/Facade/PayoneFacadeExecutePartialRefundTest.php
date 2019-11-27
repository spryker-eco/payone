<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem;
use SprykerEco\Zed\Payone\Business\PayoneFacadeInterface;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DummyAdapter;
use SprykerEcoTest\Zed\Payone\Business\PayoneFacadeMockBuilder;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;

class PayoneFacadeExecutePartialRefundTest extends Unit
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
    protected function setUp(): void
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
        $refundResponseTransfer = $this->getFacadeMock()->executePartialRefund($payonePartialOperationTransfer);
        $status = $this->tester->getFacade()->findPayoneOrderItemStatus($saveOrderTransfer->getIdSalesOrder(), $itemTransfer->getIdSalesOrderItem());

        //Assert
        $this->assertInstanceOf(RefundResponseTransfer::class, $refundResponseTransfer);
        $this->assertSame('375461930', $refundResponseTransfer->getTxid());
        $this->assertSame(static::ORDER_ITEM_STATUS_REFUND_APPROVED, $status);
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface
     */
    protected function getFacadeMock(): PayoneFacadeInterface
    {
        return (new PayoneFacadeMockBuilder())
            ->build(new DummyAdapter(static::FAKE_REFUND_RESPONSE), $this);
    }
}
