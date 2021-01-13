<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PayoneZedTester extends Actor
{
    use _generated\PayoneZedTesterActions;

    public const TEST_STATE_MACHINE_NAME = 'Test01';

    protected const FAKE_PAYMENT_METHOD = 'payment.payone.e_wallet';
    protected const FAKE_REFERENCE = 'reference';
    protected const FAKE_PAYONE_ORDER_ITEM_STATUS = 'new';

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrder(): SaveOrderTransfer
    {
        return $this->haveOrder([], static::TEST_STATE_MACHINE_NAME);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    public function createPaymentPayone(int $idSalesOrder): SpyPaymentPayone
    {
        $paymentPayoneEntity = (new SpyPaymentPayone())
           ->setFkSalesOrder($idSalesOrder)
           ->setPaymentMethod(static::FAKE_PAYMENT_METHOD)
           ->setReference(static::FAKE_REFERENCE);
        $paymentPayoneEntity->save();

        return $paymentPayoneEntity;
    }

    /**
     * @param int $idPaymentPayone
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem
     */
    public function createPaymentPayoneOrderItem(int $idPaymentPayone, int $idSalesOrderItem): SpyPaymentPayoneOrderItem
    {
        $paymentPayoneOrderItemEntity = (new SpyPaymentPayoneOrderItem())
           ->setFkPaymentPayone($idPaymentPayone)
           ->setFkSalesOrderItem($idSalesOrderItem)
           ->setStatus(static::FAKE_PAYONE_ORDER_ITEM_STATUS);

        $paymentPayoneOrderItemEntity->save();

        return $paymentPayoneOrderItemEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function createRefund(int $idSalesOrder): RefundTransfer
    {
        $salesQueryContainer = $this->getLocator()->sales()->queryContainer();
        $orderEntity = $salesQueryContainer->querySalesOrderById($idSalesOrder)->findOne();
        $orderItem = $orderEntity->getItems()->getFirst();

        return $this->getLocator()->refund()->facade()->calculateRefund([$orderItem], $orderEntity);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(int $idSalesOrder): OrderTransfer
    {
        return $this->getLocator()->sales()->facade()->findOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param array $transfersData
     * @param string $className
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\AbstractTransfer[]
     */
    protected function createTransfersCollection(array $transfersData, string $className): ArrayObject
    {
        $transfersCollection = new ArrayObject();
        foreach ($transfersData as $transferData) {
            $transfer = (new $className())
                ->fromArray($transferData, true);

            $transfersCollection->append($transfer);
        }

        return $transfersCollection;
    }

    /**
     * @param array $totalsData
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotals(array $totalsData): TotalsTransfer
    {
        return (new TotalsTransfer())
            ->fromArray($totalsData);
    }
}
