<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\ConditionChecker;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface;

class IsRefundPossibleChecker implements IsRefundPossibleCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface
     */
    protected $payonePaymentReader;

    /**
     * @var \SprykerEco\Zed\Payone\Business\ConditionChecker\IsPaymentDataRequiredCheckerInterface
     */
    protected $isPaymentDataRequiredChecker;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface $payonePaymentReader
     * @param \SprykerEco\Zed\Payone\Business\ConditionChecker\IsPaymentDataRequiredCheckerInterface $isPaymentDataRequiredChecker
     */
    public function __construct(
        PayonePaymentReaderInterface $payonePaymentReader,
        IsPaymentDataRequiredCheckerInterface $isPaymentDataRequiredChecker
    ) {
        $this->payonePaymentReader = $payonePaymentReader;
        $this->isPaymentDataRequiredChecker = $isPaymentDataRequiredChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer): bool
    {
        $paymentTransfer = $this->payonePaymentReader->getPayment($orderTransfer);

        if (!$this->isPaymentDataRequiredChecker->isPaymentDataRequired($orderTransfer)) {
            return true;
        }

        $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();

        return $paymentDetailTransfer->getBic() && $paymentDetailTransfer->getIban();
    }
}
