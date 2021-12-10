<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use ArrayObject;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerEcoTest\Zed\Payone\Business\AbstractPayoneTest;

class PayoneFacadeFindPayoneOrderItemStatusTest extends AbstractPayoneTest
{
    /**
     * @return void
     */
    public function testFindPayoneOrderItemStatusWithExistingOrderAndOrderItem(): void
    {
        //Arrange
        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $items = new ArrayObject();
        $items->append($this->orderEntity->getItems()->offsetGet(0));
        $saveOrderTransfer->setOrderItems($items);
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
