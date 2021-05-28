<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;

interface PayoneRefundRequestSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer): RefundResponseTransfer;
}
