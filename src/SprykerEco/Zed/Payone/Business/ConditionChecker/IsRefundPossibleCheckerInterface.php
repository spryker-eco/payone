<?php

namespace SprykerEco\Zed\Payone\Business\ConditionChecker;

use Generated\Shared\Transfer\OrderTransfer;

interface IsRefundPossibleCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer): bool;
}
