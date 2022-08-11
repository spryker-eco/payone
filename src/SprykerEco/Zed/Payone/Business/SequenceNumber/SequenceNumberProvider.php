<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\SequenceNumber;

use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class SequenceNumberProvider implements SequenceNumberProviderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var int
     */
    protected $defaultEmptySequenceNumber;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param int $defaultEmptySequenceNumber
     */
    public function __construct(PayoneRepositoryInterface $payoneRepository, int $defaultEmptySequenceNumber)
    {
        $this->payoneRepository = $payoneRepository;

        $this->defaultEmptySequenceNumber = $defaultEmptySequenceNumber;
    }

    /**
     * @param int $transactionId
     *
     * @return int
     */
    public function getNextSequenceNumber(int $transactionId): int
    {
        $current = $this->getCurrentSequenceNumber($transactionId);
        if ($current < 0) {
            return $current;
        }

        return $current + 1;
    }

    /**
     * @param int $transactionId
     *
     * @return int|null
     */
    public function getCurrentSequenceNumber(int $transactionId): ?int
    {
        $transactionEntity = $this->payoneRepository
            ->createCurrentSequenceNumberQuery($transactionId)
            ->findOne();

        // If we have a transactionId but no status log we return the configured default
        if (!$transactionEntity || !$transactionEntity->getSequenceNumber()) {
            return $this->defaultEmptySequenceNumber;
        }

        return $transactionEntity->getSequenceNumber();
    }
}
