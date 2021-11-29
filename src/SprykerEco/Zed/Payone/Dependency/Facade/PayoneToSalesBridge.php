<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

class PayoneToSalesBridge implements PayoneToSalesInterface
{
    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder(int $idSalesOrder): OrderTransfer
    {
        return $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function updateOrder(OrderTransfer $orderTransfer, int $idSalesOrder): bool
    {
        return $this->salesFacade->updateOrder($orderTransfer, $idSalesOrder);
    }
}
