<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;
use Generated\Shared\Transfer\PayoneOrderItemFilterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\Payone\Persistence\PayonePersistenceFactory getFactory()
 */
class PayoneRepository extends AbstractRepository implements PayoneRepositoryInterface
{
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
}
