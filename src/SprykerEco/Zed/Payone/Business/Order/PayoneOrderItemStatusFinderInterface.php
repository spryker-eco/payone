<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Order;

interface PayoneOrderItemStatusFinderInterface
{
    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return string|null
     */
    public function findPayoneOrderItemStatus(int $idSalesOrder, int $idSalesOrderItem): ?string;
}
