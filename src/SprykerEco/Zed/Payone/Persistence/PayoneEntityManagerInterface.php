<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;

interface PayoneEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer
     */
    public function createPaymentPayoneOrderItem(PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer): PaymentPayoneOrderItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer
     */
    public function updatePaymentPayoneOrderItem(PaymentPayoneOrderItemTransfer $paymentPayoneOrderItemTransfer): PaymentPayoneOrderItemTransfer;
}
