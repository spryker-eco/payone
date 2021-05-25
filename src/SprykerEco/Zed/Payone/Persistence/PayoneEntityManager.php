<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerEco\Zed\Payone\Persistence\PayonePersistenceFactory getFactory()
 */
class PayoneEntityManager extends AbstractEntityManager implements PayoneEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer
     */
    public function createPaymentPayoneOrderItem(PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer): PaymentPayoneOrderItemTransfer
    {
        $paymentPayoneOrderItemEntity = $this->getFactory()
            ->createPayonePersistenceMapper()
            ->mapPaymentPayoneOrderItemTransferToEntity($paymentPayoneOrderItemTransfer, new SpyPaymentPayoneOrderItem());

        $paymentPayoneOrderItemEntity->save();

        $paymentPayoneOrderItemTransfer->setIdPaymentPayoneOrderItem(
            $paymentPayoneOrderItemEntity->getIdPaymentPayoneOrderItem()
        );

        return $paymentPayoneOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer
     */
    public function updatePaymentPayoneOrderItem(PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer): PaymentPayoneOrderItemTransfer
    {
        $paymentPayoneOrderItemEntity = $this->getFactory()
            ->createPaymentPayoneOrderItemQuery()
            ->filterByFkSalesOrderItem($paymentPayoneOrderItemTransfer->getIdSalesOrderItem())
            ->filterByFkPaymentPayone($paymentPayoneOrderItemTransfer->getIdPaymentPayone())
            ->findOne();

        if (!$paymentPayoneOrderItemEntity) {
            return $paymentPayoneOrderItemTransfer;
        }

        $paymentPayoneOrderItemEntity = $this->getFactory()
            ->createPayonePersistenceMapper()
            ->mapPaymentPayoneOrderItemTransferToEntity($paymentPayoneOrderItemTransfer, $paymentPayoneOrderItemEntity);

        $paymentPayoneOrderItemEntity->save();

        $paymentPayoneOrderItemTransfer->setIdPaymentPayoneOrderItem(
            $paymentPayoneOrderItemEntity->getIdPaymentPayoneOrderItem()
        );

        return $paymentPayoneOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentDetailTransfer $paymentDataTransfer
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDetailTransfer $paymentDataTransfer, $idOrder): void
    {
        $paymentEntity = $this->createPaymentByOrderId($idOrder)->findOne();
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $paymentDetailEntity->fromArray($paymentDataTransfer->toArray());

        $paymentDetailEntity->save();
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    protected function createPaymentByOrderId(int $idOrder)
    {
        $query = $this->getFactory()->createPaymentPayoneQuery();
        $query->findByFkSalesOrder($idOrder);

        return $query;
    }
}
