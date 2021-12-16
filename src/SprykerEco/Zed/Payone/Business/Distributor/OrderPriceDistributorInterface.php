<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Distributor;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderPriceDistributorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function distributeOrderPrice(?OrderTransfer $orderTransfer): OrderTransfer;
}
