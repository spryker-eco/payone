<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;

/**
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacade getFacade()
 */
class PaymentIsAppointedConditionPlugin extends AbstractCondition
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $res = $this->getFacade()
            ->isPaymentAppointed($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());

        return $res;
    }
}
