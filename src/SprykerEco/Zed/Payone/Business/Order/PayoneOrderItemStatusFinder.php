<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\PayoneOrderItemFilterTransfer;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayoneOrderItemStatusFinder implements PayoneOrderItemStatusFinderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     */
    public function __construct(PayoneRepositoryInterface $payoneRepository)
    {
        $this->payoneRepository = $payoneRepository;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return string|null
     */
    public function findPayoneOrderItemStatus(int $idSalesOrder, int $idSalesOrderItem): ?string
    {
        $payoneOrderItemTransfers = $this->payoneRepository
            ->findPaymentPayoneOrderItemByFilter(
                (new PayoneOrderItemFilterTransfer())
                    ->setIdSalesOrder($idSalesOrder)
                    ->setSalesOrderItemIds([$idSalesOrderItem])
            );

        if (count($payoneOrderItemTransfers) === 0) {
            return null;
        }

        $payoneOrderItemTransfer = reset($payoneOrderItemTransfers);

        return $payoneOrderItemTransfer->getStatus();
    }
}
