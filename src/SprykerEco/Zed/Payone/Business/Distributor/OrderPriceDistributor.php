<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Distributor;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Zed\Payone\PayoneConfig;

class OrderPriceDistributor implements OrderPriceDistributorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function distributeOrderPrice(OrderTransfer $orderTransfer): OrderTransfer
    {
        if ($orderTransfer->getPayments()->count() <= 1) {
            return $orderTransfer;
        }

        $payonePayment = $this->findPayonePayment($orderTransfer);
        if (!$payonePayment) {
            return $orderTransfer;
        }

        $totals = $orderTransfer->getTotals();

        $roundingError = 0;
        $priceRatio = $this->calculatePriceRatio($payonePayment->getAmount(), $totals->getGrandTotal());

        $this->distributeOrderItemsPrices($orderTransfer, $priceRatio, $roundingError);
        $this->distributeOrderExpensesPrices($orderTransfer, $priceRatio, $roundingError);

        $orderTransfer->setTotals($totals->setGrandTotal($payonePayment->getAmount()));

        return $orderTransfer;
    }

    /**
     * @param int $paymentAmount
     * @param int $grandTotalAmount
     *
     * @return float
     */
    protected function calculatePriceRatio(int $paymentAmount, int $grandTotalAmount): float
    {
        return $paymentAmount / $grandTotalAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    protected function findPayonePayment(OrderTransfer $orderTransfer): ?PaymentTransfer
    {
        $paymentTransfers = $orderTransfer->getPayments();

        foreach ($paymentTransfers as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === PayoneConfig::PROVIDER_NAME) {
                return $paymentTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $unitPrice
     * @param float $priceRatio
     * @param float $roundingError
     *
     * @return int
     */
    protected function calculateRoundedPrice(int $unitPrice, float $priceRatio, float &$roundingError): int
    {
        $priceBeforeRound = ($unitPrice * $priceRatio) + $roundingError;
        $priceRounded = (int)round($priceBeforeRound);
        $roundingError = $priceBeforeRound - $priceRounded;

        return $priceRounded;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param float $priceRatio
     * @param float $roundingError
     *
     * @return void
     */
    protected function distributeOrderItemsPrices(
        OrderTransfer $orderTransfer,
        float $priceRatio,
        float &$roundingError
    ): void {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $priceRounded = $this->calculateRoundedPrice(
                $itemTransfer->getSumPriceToPayAggregation(),
                $priceRatio,
                $roundingError,
            );

            $itemTransfer->setSumPriceToPayAggregation($priceRounded);

            $unitPrice = $priceRounded / $itemTransfer->getQuantity();
            $itemTransfer->setUnitPriceToPayAggregation($unitPrice);
            $itemTransfer->setUnitGrossPrice($unitPrice);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param float $priceRatio
     * @param float $roundingError
     *
     * @return void
     */
    protected function distributeOrderExpensesPrices(
        OrderTransfer $orderTransfer,
        float $priceRatio,
        float &$roundingError
    ): void {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->setSumGrossPrice(
                $this->calculateRoundedPrice(
                    $expenseTransfer->getSumGrossPrice(),
                    $priceRatio,
                    $roundingError,
                ),
            );
        }
    }
}
