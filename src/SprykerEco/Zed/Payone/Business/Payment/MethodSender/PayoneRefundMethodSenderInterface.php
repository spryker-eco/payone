<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;

interface PayoneRefundMethodSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer): RefundResponseTransfer;
}
