<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\TransactionStatus;

use SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface;

interface TransactionStatusUpdateManagerInterface
{
    /**
     * @param \SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(TransactionStatusUpdateInterface $request);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentPaid($idSalesOrder, $idSalesOrderItem);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentCapture($idSalesOrder, $idSalesOrderItem);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOverpaid($idSalesOrder, $idSalesOrderItem);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentRefund($idSalesOrder, $idSalesOrderItem);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentAppointed($idSalesOrder, $idSalesOrderItem);

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOther($idSalesOrder, $idSalesOrderItem);
}
