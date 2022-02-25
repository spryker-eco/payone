<?php
// phpcs:disable Spryker.ControlStructures.DisallowCloakingCheck.FixableEmpty

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
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
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

        $totalsTransfer = $orderTransfer->getTotals();
        $priceRatio = $this->calculatePriceRatio($payonePayment->getAmountOrFail(), $totalsTransfer->getGrandTotalOrFail());

        $this->distributeOrderItemsPrices($orderTransfer, $priceRatio);
        $this->distributeOrderExpensesPrices($orderTransfer, $priceRatio);

        $orderTransfer->setTotals($totalsTransfer->setGrandTotal($payonePayment->getAmount()));

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
     *
     * @return void
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     */
    protected function distributeOrderItemsPrices(OrderTransfer $orderTransfer, float $priceRatio): void
    {
        $lastItemTransfer = null;
        $roundingError = 0;

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $unitPrice = $this->calculateRoundedPrice(
                $itemTransfer->getUnitGrossPriceOrFail(),
                $priceRatio,
                $roundingError,
            );

            $itemTransfer->setSumPriceToPayAggregation($unitPrice)
                ->setUnitPriceToPayAggregation($unitPrice)
                ->setUnitGrossPrice($unitPrice);
            $lastItemTransfer = $itemTransfer;
        }

        if ($lastItemTransfer && $roundingError) {
            $lastItemUnitPrice = (int)round($lastItemTransfer->getUnitGrossPrice() + $roundingError);
            $lastItemTransfer->setSumPriceToPayAggregation($lastItemUnitPrice)
                ->setUnitPriceToPayAggregation($lastItemUnitPrice)
                ->setUnitGrossPrice($lastItemUnitPrice);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param float $priceRatio
     *
     * @return void
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     */
    protected function distributeOrderExpensesPrices(OrderTransfer $orderTransfer, float $priceRatio): void
    {
        $lastExpenseTransfer = null;
        $roundingError = 0;

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $roundedPrice = $this->calculateRoundedPrice(
                $expenseTransfer->getSumGrossPriceOrFail(),
                $priceRatio,
                $roundingError,
            );

            $expenseTransfer->setSumGrossPrice($roundedPrice);
            $lastExpenseTransfer = $expenseTransfer;
        }

        if ($lastExpenseTransfer && $roundingError) {
            $lastExpenseSumGrossPrice = (int)round($lastExpenseTransfer->getSumGrossPrice() + $roundingError);
            $lastExpenseTransfer->setSumGrossPrice($lastExpenseSumGrossPrice);
        }
    }
}
