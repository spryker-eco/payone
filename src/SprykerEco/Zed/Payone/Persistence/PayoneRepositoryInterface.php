<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;
use Generated\Shared\Transfer\PayoneOrderItemFilterTransfer;

interface PayoneRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer[]
     */
    public function findPaymentPayoneOrderItemByFilter(PayoneOrderItemFilterTransfer $payoneOrderItemFilerTransfer): array;
}
