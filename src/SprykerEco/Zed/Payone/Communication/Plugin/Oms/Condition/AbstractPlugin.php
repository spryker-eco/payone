<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

/**
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
abstract class AbstractPlugin extends BaseAbstractPlugin implements ConditionInterface
{
    /**
     * @var string
     */
    public const NAME = 'AbstractPlugin';

    /**
     * @var array
     */
    protected static $resultCache = [];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $order = $orderItem->getOrder();

        if (isset(static::$resultCache[$this->getName()][$order->getPrimaryKey()])) {
            return static::$resultCache[$this->getName()][$order->getPrimaryKey()];
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($order->toArray(), true);

        $isSuccess = $this->callFacade($orderTransfer);
        static::$resultCache[$order->getPrimaryKey()] = $isSuccess;

        return $isSuccess;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    abstract protected function callFacade(OrderTransfer $orderTransfer): bool;

    /**
     * @return string
     */
    protected function getName(): string
    {
        return static::NAME;
    }
}
