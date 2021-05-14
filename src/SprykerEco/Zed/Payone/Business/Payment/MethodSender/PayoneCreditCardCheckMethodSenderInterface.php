<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\CreditCardCheckResponseTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;

interface PayoneCreditCardCheckMethodSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData): CreditCardCheckResponseTransfer;
}
