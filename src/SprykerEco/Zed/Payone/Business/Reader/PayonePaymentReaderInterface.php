<?php

namespace SprykerEco\Zed\Payone\Business\Reader;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;

interface PayonePaymentReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     */
    public function getPayment(OrderTransfer $orderTransfer): PayonePaymentTransfer;
}
