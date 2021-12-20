<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Dependency\Facade;

use Propel\Runtime\Collection\ObjectCollection;

interface PayoneToOmsInterface
{
    /**
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent(string $eventId, ObjectCollection $orderItems, array $logContext, array $data = []): array;

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlaggedAll(int $idOrder, string $flag): bool;
}
