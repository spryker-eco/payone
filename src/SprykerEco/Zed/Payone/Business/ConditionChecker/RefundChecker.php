<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\ConditionChecker;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class RefundChecker implements RefundCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var \SprykerEco\Zed\Payone\Business\ConditionChecker\PaymentDataCheckerInterface
     */
    protected $paymentDataChecker;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\Business\ConditionChecker\PaymentDataCheckerInterface $paymentDataChecker
     */
    public function __construct(
        PayoneRepositoryInterface $payoneRepository,
        PaymentDataCheckerInterface $paymentDataChecker
    ) {
        $this->payoneRepository = $payoneRepository;
        $this->paymentDataChecker = $paymentDataChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer): bool
    {
        $paymentTransfer = $this->payoneRepository->getPayonePaymentByOrder($orderTransfer);

        if (!$this->paymentDataChecker->isPaymentDataRequired($orderTransfer)) {
            return true;
        }

        $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();

        return $paymentDetailTransfer->getBic() && $paymentDetailTransfer->getIban();
    }
}
