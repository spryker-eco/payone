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
     * @api
     *
     * @param int $idPaymentPayone
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createCurrentSequenceNumberQuery(int $idPaymentPayone);

    /**
     * @api
     *
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByTransactionIdQuery($transactionId);

    /**
     * @api
     *
     * @param string $invoiceTitle
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByInvoiceTitleAndCustomerIdQuery($invoiceTitle, $customerId);

    /**
     * @api
     *
     * @param string $fileReference
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByFileReferenceAndCustomerIdQuery($fileReference, $customerId);

    /**
     * @api
     *
     * @param int $fkPayment
     * @param string $requestType
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestType);

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByOrderId($idOrder);

    /**
     * @api
     *
     * @param int $orderId
     * @param string $request
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIdAndRequest($orderId, $request);

    /**
     * @api
     *
     * @param int $orderId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentById($orderId);

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createTransactionStatusLogsBySalesOrder($idSalesOrder);

    /**
     * @api
     *
     * @param int $idSalesOrderItem
     * @param array $ids
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery
     */
    public function createTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, $ids);

    /**
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIds($orders);

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createLastApiLogsByOrderId($idSalesOrder);

    /**
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createTransactionStatusLogsByOrderIds($orders);

    /**
     * @api
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLogQuery
     */
    public function createApiCallLog();
}
