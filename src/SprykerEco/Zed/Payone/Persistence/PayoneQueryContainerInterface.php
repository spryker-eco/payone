<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface PayoneQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $idPaymentPayone
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createCurrentSequenceNumberQuery($idPaymentPayone);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByTransactionIdQuery($transactionId);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param string $invoiceTitle
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByInvoiceTitleAndCustomerIdQuery($invoiceTitle, $customerId);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param string $fileReference
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByFileReferenceAndCustomerIdQuery($fileReference, $customerId);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $fkPayment
     * @param string $requestType
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestType);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByOrderId($idOrder);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $orderId
     * @param string $request
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIdAndRequest($orderId, $request);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $orderId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentById($orderId);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createTransactionStatusLogsBySalesOrder($idSalesOrder);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $idSalesOrderItem
     * @param array $ids
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery
     */
    public function createTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, $ids);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIds($orders);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createLastApiLogsByOrderId($idSalesOrder);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createTransactionStatusLogsByOrderIds($orders);

    /**
     * Specification:
     * - Todo
     *
     * @api
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLogQuery
     */
    public function createApiCallLog();
}
