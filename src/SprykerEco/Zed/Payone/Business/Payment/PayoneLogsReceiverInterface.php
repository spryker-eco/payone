<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer;

interface PayoneLogsReceiverInterface
{
    /**
     * Gets payment logs (both api and transaction status) for specific orders in chronological order.
     *
     * @param \Propel\Runtime\Collection\ObjectCollection|\ArrayObject $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs($orders): PayonePaymentLogCollectionTransfer;
}
