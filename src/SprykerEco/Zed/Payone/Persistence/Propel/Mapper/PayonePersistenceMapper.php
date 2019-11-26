<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;
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
}
