<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\DebitResponseTransfer;

interface PayoneDebitMethodSenderInterface
{
    /**
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function debitPayment(int $idPayment): DebitResponseTransfer;
}
