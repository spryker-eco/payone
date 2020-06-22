<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition;

use DateInterval;
use DateTime;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as SprykerAbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class IsFullOrderCancelledConditionPlugin extends SprykerAbstractPlugin implements ConditionInterface
{
    protected const STATE_MACHINE_FLAG_CANCELLED = 'cancelled';

    /**
     * {@inheritDoc}
     * - Checks if 1 hour passed after order was placed.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        return $this->getFactory()
            ->getOmsFacade()
            ->isOrderFlaggedAll($orderItem->getFkSalesOrder(), static::STATE_MACHINE_FLAG_CANCELLED);
    }
}
