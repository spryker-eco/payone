<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\TransactionStatus;

use SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface;
use SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;

interface TransactionStatusUpdateManagerInterface
{
    /**
     * @param \SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(TransactionStatusUpdateInterface $request): TransactionStatusResponse;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentNotificationAvailable(int $idSalesOrder, int $idSalesOrderItem): bool;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentPaid(int $idSalesOrder, int $idSalesOrderItem): bool;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentCapture(int $idSalesOrder, int $idSalesOrderItem): bool;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOverpaid(int $idSalesOrder, int $idSalesOrderItem): bool;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentUnderpaid(int $idSalesOrder, int $idSalesOrderItem): bool;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentRefund(int $idSalesOrder, int $idSalesOrderItem): bool;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentAppointed(int $idSalesOrder, int $idSalesOrderItem): bool;

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOther(int $idSalesOrder, int $idSalesOrderItem): bool;
}
