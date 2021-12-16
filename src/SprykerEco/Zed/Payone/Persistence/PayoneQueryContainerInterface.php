<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLogQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface PayoneQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idPaymentPayone
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createCurrentSequenceNumberQuery(int $idPaymentPayone): SpyPaymentPayoneApiLogQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByTransactionIdQuery(int $transactionId): SpyPaymentPayoneQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string|null $invoiceTitle
     * @param int|null $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByInvoiceTitleAndCustomerIdQuery(?string $invoiceTitle, ?int $customerId): SpyPaymentPayoneQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string|null $fileReference
     * @param int|null $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByFileReferenceAndCustomerIdQuery(?string $fileReference, ?int $customerId): SpyPaymentPayoneQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $fkPayment
     * @param string $requestType
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogByPaymentAndRequestTypeQuery(int $fkPayment, string $requestType): SpyPaymentPayoneApiLogQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentByOrderId(int $idOrder): SpyPaymentPayoneQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $orderId
     * @param string $request
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIdAndRequest(int $orderId, string $request): SpyPaymentPayoneApiLogQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int|null $orderId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentById(?int $orderId): SpyPaymentPayoneQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createTransactionStatusLogsBySalesOrder(int $idSalesOrder): SpyPaymentPayoneTransactionStatusLogQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     * @param array $ids
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery
     */
    public function createTransactionStatusLogOrderItemsByLogIds(
        int $idSalesOrderItem,
        array $ids
    ): SpyPaymentPayoneTransactionStatusLogOrderItemQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIds(ObjectCollection $orders): SpyPaymentPayoneApiLogQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createLastApiLogsByOrderId(int $idSalesOrder): SpyPaymentPayoneApiLogQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrder> $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createTransactionStatusLogsByOrderIds(array $orders): SpyPaymentPayoneTransactionStatusLogQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLogQuery
     */
    public function createApiCallLog(): SpyPaymentPayoneApiCallLogQuery;
}
