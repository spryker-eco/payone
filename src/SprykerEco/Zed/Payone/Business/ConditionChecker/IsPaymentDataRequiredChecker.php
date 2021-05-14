<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\ConditionChecker;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface;

class IsPaymentDataRequiredChecker implements IsPaymentDataRequiredCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface
     */
    protected $payonePaymentReader;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface $payonePaymentReader
     */
    public function __construct(PayonePaymentReaderInterface $payonePaymentReader)
    {
        $this->payonePaymentReader = $payonePaymentReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer): bool
    {
        $paymentTransfer = $this->payonePaymentReader->getPayment($orderTransfer);

        // Return early if we don't need the iban or bic data
        $paymentMethod = $paymentTransfer->getPaymentMethod();
        $whiteList = [
            PayoneApiConstants::PAYMENT_METHOD_E_WALLET,
            PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO,
            PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        ];

        if (in_array($paymentMethod, $whiteList)) {
            return false;
        }

        return true;
    }
}
