<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\DebitResponseTransfer;

interface PayoneDebitRequestSenderInterface
{
    /**
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function debitPayment(int $idPayment): DebitResponseTransfer;
}
