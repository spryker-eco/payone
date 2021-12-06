<?php
namespace SprykerEcoTest\Zed\Payone;

use Codeception\Actor;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
*/
class PayoneBusinessTester extends \Codeception\Actor
{
    use _generated\PayoneBusinessTesterActions;

    /**
     * @var string
     */
    public const TEST_STATE_MACHINE_NAME = 'Test01';

    /**
     * @var string
     */
    protected const FAKE_PAYMENT_METHOD = 'payment.payone.e_wallet';

    /**
     * @var string
     */
    protected const FAKE_REFERENCE = 'reference';

    /**
     * @var string
     */
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
            ->setTransactionId(1)
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
}
