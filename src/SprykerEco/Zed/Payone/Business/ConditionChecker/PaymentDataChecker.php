<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\ConditionChecker;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PaymentDataChecker implements PaymentDataCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected $payoneConfig;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\PayoneConfig $payoneConfig
     */
    public function __construct(PayoneRepositoryInterface $payoneRepository, PayoneConfig $payoneConfig)
    {
        $this->payoneRepository = $payoneRepository;
        $this->payoneConfig = $payoneConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer): bool
    {
        $paymentTransfer = $this->payoneRepository->getPayonePaymentByOrder($orderTransfer);

        $paymentMethod = $paymentTransfer->getPaymentMethod();

        if (in_array($paymentMethod, $this->payoneConfig->getPaymentMethodsWithOptionalPaymentData())) {
            return false;
        }

        return true;
    }
}
