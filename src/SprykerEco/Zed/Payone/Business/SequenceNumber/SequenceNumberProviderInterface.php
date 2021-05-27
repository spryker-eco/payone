<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\SequenceNumber;

interface SequenceNumberProviderInterface
{
    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getNextSequenceNumber(string $transactionId): int;

    /**
     * @param string $transactionId
     *
     * @return int|null
     */
    public function getCurrentSequenceNumber(string $transactionId): ?int;
}
