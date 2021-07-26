<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\SequenceNumber;

interface SequenceNumberProviderInterface
{
    /**
     * @param int $transactionId
     *
     * @return int
     */
    public function getNextSequenceNumber(int $transactionId): int;

    /**
     * @param int $transactionId
     *
     * @return int|null
     */
    public function getCurrentSequenceNumber(int $transactionId): ?int;
}
