<?php
// phpcs:disable SprykerStrict.TypeHints.ParameterTypeHint.MissingNativeTypeHint

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use ArrayObject;
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
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\Payone\Persistence\PayonePersistenceFactory getFactory()
 */
class PayoneRepository extends AbstractRepository implements PayoneRepositoryInterface
{
    /**
     * @var string
     */
    protected const LOG_TYPE_API_LOG = 'SpyPaymentPayoneApiLog';

    /**
     * @var string
     */
    protected const LOG_TYPE_TRANSACTION_STATUS_LOG = 'SpyPaymentPayoneTransactionStatusLog';

    /**
     * @param \Generated\Shared\Transfer\PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer
     *
     * @return array<\Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer>
     */
    public function findPaymentPayoneOrderItemByFilter(PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer): array
    {
        $paymentPayoneOrderItemQuery = $this->getFactory()->createPaymentPayoneOrderItemQuery();
        $paymentPayoneOrderItemQuery = $this->setPayoneOrderItemFilters(
            $paymentPayoneOrderItemQuery,
            $payoneOrderItemFilerTransfer,
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
        $paymentPayoneEntity = $this->createPaymentPayoneQueryByOrderId($orderTransfer->getIdSalesOrder())->findOne();

        return $this
            ->getFactory()
            ->createPayonePersistenceMapper()
            ->mapPaymentPayoneEntityToPayonePaymentTransfer($paymentPayoneEntity, new PayonePaymentTransfer());
    }

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail(int $idOrder): PaymentDetailTransfer
    {
        $paymentPayoneEntity = $this->createPaymentPayoneQueryByOrderId($idOrder)->findOne();
        $paymentPayoneDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        return $this->getFactory()->createPayonePersistenceMapper()->mapPaymentPayoneDetailToPaymentDetailTransfer($paymentPayoneDetailEntity);
    }

    /**
     * Gets payment logs (both api and transaction status) for specific orders in chronological order.
     *
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs(ArrayObject $orderTransfers): PayonePaymentLogCollectionTransfer
    {
        $paymentPayoneApiLogEntityCollection = $this->createPaymentPayoneApiLogQueryByOrderIds($orderTransfers)->find()->getData();

        $paymentPayoneTransactionStatusLogEntityCollection = $this->createPaymentPayoneTransactionStatusLogQueryByOrderIds($orderTransfers)->find()->getData();

        $payonePaymentLogTransferList = [];
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $paymentPayoneApiLogEntity */
        foreach ($paymentPayoneApiLogEntityCollection as $paymentPayoneApiLogEntity) {
            /** @var \DateTime $apiLogCreatedAtDateTime */
            $apiLogCreatedAtDateTime = $paymentPayoneApiLogEntity->getCreatedAt();
            $key = $apiLogCreatedAtDateTime->format('Y-m-d\TH:i:s\Z') . 'a' . $paymentPayoneApiLogEntity->getIdPaymentPayoneApiLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($paymentPayoneApiLogEntity->toArray(), true);
            $payonePaymentLogTransfer->setLogType(static::LOG_TYPE_API_LOG);
            $payonePaymentLogTransferList[$key] = $payonePaymentLogTransfer;
        }
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $paymentPayoneTransactionStatusLogEntity */
        foreach ($paymentPayoneTransactionStatusLogEntityCollection as $paymentPayoneTransactionStatusLogEntity) {
            /** @var \DateTime $transactionStatusLogDateTime */
            $transactionStatusLogDateTime = $paymentPayoneTransactionStatusLogEntity->getCreatedAt();
            $key = $transactionStatusLogDateTime->format('Y-m-d\TH:i:s\Z') . 't' . $paymentPayoneTransactionStatusLogEntity->getIdPaymentPayoneTransactionStatusLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($paymentPayoneTransactionStatusLogEntity->toArray(), true);
            $payonePaymentLogTransfer->setLogType(static::LOG_TYPE_TRANSACTION_STATUS_LOG);
            $payonePaymentLogTransferList[$key] = $payonePaymentLogTransfer;
        }

        ksort($payonePaymentLogTransferList);

        $payonePaymentLogCollectionTransfer = new PayonePaymentLogCollectionTransfer();

        foreach ($payonePaymentLogTransferList as $payonePaymentLogTransfer) {
            $payonePaymentLogCollectionTransfer->addPaymentLog($payonePaymentLogTransfer);
        }

        return $payonePaymentLogCollectionTransfer;
    }

    /**
     * @param int|null $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PayoneApiLogTransfer|null
     */
    public function findLastApiLogByOrderId(?int $idSalesOrder): ?PayoneApiLogTransfer
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
     * @param int|null $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentPayoneQueryByOrderId(?int $idOrder): SpyPaymentPayoneQuery
    {
        $query = $this->getFactory()->createPaymentPayoneQuery();

        if ($idOrder !== null) {
            $query->findByFkSalesOrder($idOrder);
        }

        return $query;
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
                $payoneOrderItemFilerTransfer->getSalesOrderItemIds(),
            );
        }

        return $paymentPayoneOrderItemQuery;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    protected function createPaymentPayoneApiLogQueryByOrderIds($orderTransfers): SpyPaymentPayoneApiLogQuery
    {
        $ids = [];
        foreach ($orderTransfers as $orderTransfer) {
            $ids[] = $orderTransfer->getIdSalesOrder();
        }

        $query = $this->getFactory()->createPaymentPayoneApiLogQuery()
            ->useSpyPaymentPayoneQuery()
                ->filterByFkSalesOrder($ids, Criteria::IN)
            ->endUse()
            ->orderByCreatedAt();

        return $query;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    protected function createPaymentPayoneTransactionStatusLogQueryByOrderIds(ArrayObject $orderTransfers): SpyPaymentPayoneTransactionStatusLogQuery
    {
        $ids = [];
        foreach ($orderTransfers as $orderTransfer) {
            $ids[] = $orderTransfer->getIdSalesOrder();
        }

        $query = $this->getFactory()->createPaymentPayoneTransactionStatusLogQuery()
            ->useSpyPaymentPayoneQuery()
                ->filterByFkSalesOrder($ids, Criteria::IN)
            ->endUse()
            ->orderByCreatedAt();

        return $query;
    }
}
