<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Codeception\Test\Unit;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;

class PayoneFacadeFindPayoneOrderItemStatusTest extends Unit
{
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
    public function testFindPayoneOrderItemStatusWithExistingOrderAndOrderItem(): void
    {
        //Arrange
        $saveOrderTransfer = $this->tester->createOrder();
        $paymentPayoneEntity = $this->tester->createPaymentPayone($saveOrderTransfer->getIdSalesOrder());
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);
        $this->tester->createPaymentPayoneOrderItem($paymentPayoneEntity->getIdPaymentPayone(), $itemTransfer->getIdSalesOrderItem());

        //Act
        $status = $this->tester->getFacade()->findPayoneOrderItemStatus($saveOrderTransfer->getIdSalesOrder(), $itemTransfer->getIdSalesOrderItem());

        //Assert
        $this->assertNotNull($status);
    }

    /**
     * @return void
     */
    public function testFindPayoneOrderItemStatusReturnsNull(): void
    {
        //Arrange
        $idSalesOrder = random_int(10, 1000);
        $idSalesOrderItem = random_int(10, 1000);

        //Act
        $status = $this->tester->getFacade()->findPayoneOrderItemStatus($idSalesOrder, $idSalesOrderItem);

        //Assert
        $this->assertNull($status);
    }
}
