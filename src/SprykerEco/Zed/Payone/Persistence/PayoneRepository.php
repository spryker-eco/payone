<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;
use Generated\Shared\Transfer\PayoneApiLogTransfer;
use Generated\Shared\Transfer\PayoneOrderItemFilterTransfer;
use Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer;
use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\Payone\Persistence\PayonePersistenceFactory getFactory()
 */
class PayoneRepository extends AbstractRepository implements PayoneRepositoryInterface
{
    protected const LOG_TYPE_API_LOG = 'SpyPaymentPayoneApiLog';
    protected const LOG_TYPE_TRANSACTION_STATUS_LOG = 'SpyPaymentPayoneTransactionStatusLog';

    /**
     * @param \Generated\Shared\Transfer\PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer[]
     */
    public function findPaymentPayoneOrderItemByFilter(PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer): array
    {
        $paymentPayoneOrderItemQuery = $this->getFactory()->createPaymentPayoneOrderItemQuery();
        $paymentPayoneOrderItemQuery = $this->setPayoneOrderItemFilters(
            $paymentPayoneOrderItemQuery,
            $payoneOrderItemFilerTransfer
        );

        $paymentPayoneOrderItemEntities = $paymentPayoneOrderItemQuery->find();
        $paymentPayoneOrderItemTransfers = [];

        foreach ($paymentPayoneOrderItemEntities as $paymentPayoneOrderItemEntity) {
            $paymentPayoneOrderItemTransfers[] = $this->getFactory()
                ->createPayonePersistenceMapper()
                ->mapEntityToPaymentPayoneOrderItemTransfer($paymentPayoneOrderItemEntity, new PaymentPayoneOrderItemTransfer());
        }

        return $paymentPayoneOrderItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     */
    public function getPayonePaymentByOrder(OrderTransfer $orderTransfer): PayonePaymentTransfer
    {
        $payment = $this->createPaymentByOrderId($orderTransfer->getIdSalesOrder())->findOne();
        $paymentDetail = $payment->getSpyPaymentPayoneDetail();

        $paymentDetailTransfer = new PaymentDetailTransfer();
        $paymentDetailTransfer->fromArray($paymentDetail->toArray(), true);

        $paymentTransfer = new PayonePaymentTransfer();
        $paymentTransfer->fromArray($payment->toArray(), true);
        $paymentTransfer->setPaymentDetail($paymentDetailTransfer);

        return $paymentTransfer;
    }

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail(int $idOrder): PaymentDetailTransfer
    {
        $paymentEntity = $this->createPaymentByOrderId($idOrder)->findOne();
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();
        $paymentDetailTransfer = new PaymentDetailTransfer();
        $paymentDetailTransfer->fromArray($paymentDetailEntity->toArray(), true);

        return $paymentDetailTransfer;
    }

    /**
     * Gets payment logs (both api and transaction status) for specific orders in chronological order.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs($orders): PayonePaymentLogCollectionTransfer
    {
        $apiLogs = $this->createApiLogsByOrderIds($orders)->find()->getData();

        $transactionStatusLogs = $this->createTransactionStatusLogsByOrderIds($orders)->find()->getData();

        $logs = [];
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLog */
        foreach ($apiLogs as $apiLog) {
            /** @var \DateTime $apiLogCreatedAtDateTime */
            $apiLogCreatedAtDateTime = $apiLog->getCreatedAt();
            $key = $apiLogCreatedAtDateTime->format('Y-m-d\TH:i:s\Z') . 'a' . $apiLog->getIdPaymentPayoneApiLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($apiLog->toArray(), true);
            $payonePaymentLogTransfer->setLogType(static::LOG_TYPE_API_LOG);
            $logs[$key] = $payonePaymentLogTransfer;
        }
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
        foreach ($transactionStatusLogs as $transactionStatusLog) {
            /** @var \DateTime $transactionStatusLogDateTime */
            $transactionStatusLogDateTime = $transactionStatusLog->getCreatedAt();
            $key = $transactionStatusLogDateTime->format('Y-m-d\TH:i:s\Z') . 't' . $transactionStatusLog->getIdPaymentPayoneTransactionStatusLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($transactionStatusLog->toArray(), true);
            $payonePaymentLogTransfer->setLogType(static::LOG_TYPE_TRANSACTION_STATUS_LOG);
            $logs[$key] = $payonePaymentLogTransfer;
        }

        ksort($logs);

        $payonePaymentLogCollectionTransfer = new PayonePaymentLogCollectionTransfer();

        foreach ($logs as $log) {
            $payonePaymentLogCollectionTransfer->addPaymentLog($log);
        }

        return $payonePaymentLogCollectionTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PayoneApiLogTransfer
     */
    public function createLastApiLogsByOrderId(int $idSalesOrder): ?PayoneApiLogTransfer
    {
        $paymentPayoneApiLogEntity = $this->getFactory()->createPaymentPayoneApiLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->endUse()
            ->orderByCreatedAt(Criteria::DESC)
            ->orderByIdPaymentPayoneApiLog(Criteria::DESC)
            ->findOne();

        if (!$paymentPayoneApiLogEntity) {
            return null;
        }

        $apiLogTransfer = new PayoneApiLogTransfer();
        $apiLogTransfer->fromArray($paymentPayoneApiLogEntity->toArray(), true);

        return $apiLogTransfer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItemQuery $paymentPayoneOrderItemQuery
     * @param \Generated\Shared\Transfer\PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItemQuery
     */
    protected function setPayoneOrderItemFilters(
        SpyPaymentPayoneOrderItemQuery $paymentPayoneOrderItemQuery,
        PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer
    ): SpyPaymentPayoneOrderItemQuery {
        if ($payoneOrderItemFilerTransfer->getIdSalesOrder()) {
            $paymentPayoneOrderItemQuery
                ->useSpyPaymentPayoneQuery()
                    ->filterByFkSalesOrder($payoneOrderItemFilerTransfer->getIdSalesOrder())
                ->endUse();
        }

        if (count($payoneOrderItemFilerTransfer->getSalesOrderItemIds())) {
            $paymentPayoneOrderItemQuery->filterByFkSalesOrderItem_In(
                $payoneOrderItemFilerTransfer->getSalesOrderItemIds()
            );
        }

        return $paymentPayoneOrderItemQuery;
    }

    /**
     * @param int $idOrder
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    protected function createPaymentByOrderId(int $idOrder)
    {
        $query = $this->getFactory()->createPaymentPayoneQuery();
        $query->findByFkSalesOrder($idOrder);

        return $query;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    protected function createApiLogsByOrderIds($orders): SpyPaymentPayoneApiLogQuery
    {
        $ids = [];
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrder $order */
        foreach ($orders as $order) {
            $ids[] = $order->getIdSalesOrder();
        }

        $query = $this->getFactory()->createPaymentPayoneApiLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($ids, Criteria::IN)
            ->endUse()
            ->orderByCreatedAt();

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    protected function createTransactionStatusLogsByOrderIds($orders)
    {
        $ids = [];
        foreach ($orders as $order) {
            $ids[] = $order->getIdSalesOrder();
        }

        $query = $this->getFactory()->createPaymentPayoneTransactionStatusLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($ids, Criteria::IN)
            ->endUse()
            ->orderByCreatedAt();

        return $query;
    }
}
