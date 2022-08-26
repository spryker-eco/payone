<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Log;

use Propel\Runtime\Collection\ObjectCollection;

interface PaymentLogReceiverPluginInterface
{
    /**
     * Specification:
     * - This plugin fetches log entries for given orders.
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs(ObjectCollection $orders);
}
