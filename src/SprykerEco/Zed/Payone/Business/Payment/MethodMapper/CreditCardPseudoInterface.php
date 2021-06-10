<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;

interface CreditCardPseudoInterface extends PaymentMethodMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $payoneCreditCardTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer
     */
    public function mapCreditCardCheck(PayoneCreditCardTransfer $payoneCreditCardTransfer): CreditCardCheckContainer;
}
