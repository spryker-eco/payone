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
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
 */
class PartialCaptureCommandByOrderPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Performs partial capture call to Payone API.
     * - Requires `PayonePartialOperationRequestTransfer.order.idSalesOrder` to be set.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderTransfer = $this->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());

        $payonePartialOperationTransfer = (new PayonePartialOperationRequestTransfer())
            ->setOrder($orderTransfer);

        foreach ($orderItems as $orderItem) {
            $payonePartialOperationTransfer->addSalesOrderItemId($orderItem->getIdSalesOrderItem());
        }

        $this->getFacade()->executePartialCapture($payonePartialOperationTransfer);

        return [];
    }
}
