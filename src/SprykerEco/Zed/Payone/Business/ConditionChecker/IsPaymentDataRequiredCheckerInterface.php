<?php

namespace SprykerEco\Zed\Payone\Business\ConditionChecker;

use Generated\Shared\Transfer\OrderTransfer;

interface IsPaymentDataRequiredCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer): bool;
}
