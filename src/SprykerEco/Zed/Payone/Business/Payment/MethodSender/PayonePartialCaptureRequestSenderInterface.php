<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;

interface PayonePartialCaptureRequestSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function executePartialCapture(PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer): CaptureResponseTransfer;
}
