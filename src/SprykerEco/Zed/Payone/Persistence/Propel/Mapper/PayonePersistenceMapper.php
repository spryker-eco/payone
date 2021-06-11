<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem;

class PayonePersistenceMapper
{
    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem $paymentPayoneOrderItemEntity
     * @param \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer
     */
    public function mapEntityToPaymentPayoneOrderItemTransfer(
        SpyPaymentPayoneOrderItem $paymentPayoneOrderItemEntity,
        PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer
    ): PaymentPayoneOrderItemTransfer {
        $paymentPayoneOrderItemTransfer
            ->fromArray($paymentPayoneOrderItemEntity->toArray(), true)
            ->setIdPaymentPayone($paymentPayoneOrderItemEntity->getFkPaymentPayone())
            ->setIdSalesOrderItem($paymentPayoneOrderItemEntity->getFkSalesOrderItem());

        return $paymentPayoneOrderItemTransfer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \Generated\Shared\Transfer\PayonePaymentTransfer $payonePaymentTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     */
    public function mapPaymentPayoneEntityToPayonePaymentTransfer(
        SpyPaymentPayone $paymentPayoneEntity,
        PayonePaymentTransfer $payonePaymentTransfer
    ): PayonePaymentTransfer {
        $payonePaymentTransfer->fromArray($paymentPayoneEntity->toArray(), true);
        $paymentDetailTransfer = $this->mapPaymentPayoneDetailToPaymentDetailTransfer($paymentPayoneEntity->getSpyPaymentPayoneDetail());
        $payonePaymentTransfer->setPaymentDetail($paymentDetailTransfer);

        return $payonePaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem $paymentPayoneOrderItemEntity
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItem
     */
    public function mapPaymentPayoneOrderItemTransferToEntity(
        PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer,
        SpyPaymentPayoneOrderItem $paymentPayoneOrderItemEntity
    ): SpyPaymentPayoneOrderItem {
        $paymentPayoneOrderItemEntity->fromArray(
            $paymentPayoneOrderItemTransfer->modifiedToArray()
        );

        $paymentPayoneOrderItemEntity
            ->setFkSalesOrderItem($paymentPayoneOrderItemTransfer->getIdSalesOrderItem())
            ->setFkPaymentPayone($paymentPayoneOrderItemTransfer->getIdPaymentPayone());

        return $paymentPayoneOrderItemEntity;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail $paymentPayoneDetailEntity
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function mapPaymentPayoneDetailToPaymentDetailTransfer(SpyPaymentPayoneDetail $paymentPayoneDetailEntity): PaymentDetailTransfer
    {
        $paymentDetailTransfer = new PaymentDetailTransfer();

        return $paymentDetailTransfer->fromArray($paymentPayoneDetailEntity->toArray(), true);
    }
}
