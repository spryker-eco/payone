<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Payone;

use Generated\Shared\Transfer\OrderTransfer;

interface PayoneServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function distributeOrderPrice(OrderTransfer $orderTransfer): OrderTransfer;
}
