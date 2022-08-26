<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as SprykerAbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use SprykerEco\Shared\Payone\PayoneTransactionStatusConstants;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
 */
class PartialRefundIsErrorConditionPlugin extends SprykerAbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Checks if payone order item status is `refund failed`.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $payoneOrderItemStatus = $this->getFacade()
            ->findPayoneOrderItemStatus($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());

        return $payoneOrderItemStatus === PayoneTransactionStatusConstants::STATUS_REFUND_FAILED;
    }
}
