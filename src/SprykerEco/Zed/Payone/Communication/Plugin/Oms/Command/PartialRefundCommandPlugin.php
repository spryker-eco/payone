<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 */
class PartialRefundCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $refundTransfer = $this->getFactory()->getRefundFacade()->calculateRefund($orderItems, $orderEntity);
        $orderTransfer = $this->getFactory()->getSalesFacade()->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());

        $payonePartialOperationTransfer = (new PayonePartialOperationRequestTransfer())
            ->setOrder($orderTransfer)
            ->setRefund($refundTransfer);

        foreach ($orderItems as $orderItem) {
            $payonePartialOperationTransfer->addSalesOrderItemId($orderItem->getIdSalesOrderItem());
        }

        $this->getFacade()->executePartialRefund($payonePartialOperationTransfer);

        return [];
    }
}
