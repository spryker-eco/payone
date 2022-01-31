<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneApiLogTransfer;
use Generated\Shared\Transfer\PayoneOrderItemFilterTransfer;
use Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery;

interface PayoneRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer
     *
     * @return array<\Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer>
     */
    public function findPaymentPayoneOrderItemByFilter(PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     */
    public function getPayonePaymentByOrder(OrderTransfer $orderTransfer): PayonePaymentTransfer;

    /**
     * @param int|null $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PayoneApiLogTransfer|null
     */
    public function findLastApiLogByOrderId(?int $idSalesOrder): ?PayoneApiLogTransfer;

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail(int $idOrder): PaymentDetailTransfer;

    /**
     * Gets payment logs (both api and transaction status) for specific orders in chronological order.
     *
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs(ArrayObject $orderTransfers): PayonePaymentLogCollectionTransfer;

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentPayoneQueryByOrderId(int $idOrder): SpyPaymentPayoneQuery;
}
