<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEcoTest\Zed\Payone\Business\DataProvider\PayoneDistributePricesDataProvider;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Facade
 * @group PayoneFacadeDistributePricesTest
 */
class PayoneFacadeDistributePricesTest extends Unit
{
    use PayoneDistributePricesDataProvider;

    protected const PAYMENT_PROVIDER_PAYONE = 'Payone';
    protected const PAYMENT_PROVIDER_DUMMY = 'Dummy';

    /**
     * @var \SprykerEcoTest\Zed\Payone\PayoneZedTester
     */
    protected $tester;

    /**
     * @dataProvider provideOrdersData
     *
     * @param array $orderItemsData
     * @param array $expensesData
     * @param array $paymentsData
     * @param array $totalData
     * @param int $expectedTotalPrice
     *
     * @return void
     */
    public function testDistributePricesShouldReturnOrderTransferWithDistributedPrices(
        array $orderItemsData,
        array $expensesData,
        array $paymentsData,
        array $totalData,
        int $expectedTotalPrice
    ): void {
        // Arrange
        $orderTransfer = $this->tester->createOrderTransfer($orderItemsData, $expensesData, $paymentsData, $totalData);

        // Act
        $orderTransfer = $this->tester
            ->getFacade()
            ->distributePrices($orderTransfer);

        // Assert
        $itemsPrices = $this->getOrderItemsPrices($orderTransfer);
        $expensesPrices = $this->getOrderExpensesTotalPrice($orderTransfer);

        $this->assertSame(
            $expectedTotalPrice,
            $orderTransfer->getTotals()->getGrandTotal(),
            'Grand total should be equals to the expected price.'
        );

        $this->assertSame(
            $expectedTotalPrice,
            (int)$itemsPrices[ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION] + $expensesPrices,
            'Items and expenses total price should be equal to the expected price.'
        );

        $this->assertSame(
            $expectedTotalPrice,
            (int)$itemsPrices[ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION] + $expensesPrices,
            'Items unit to aggregation and expenses total price should be equals to the expected price.'
        );

        $this->assertSame(
            $expectedTotalPrice,
            (int)$itemsPrices[ItemTransfer::UNIT_GROSS_PRICE] + $expensesPrices,
            'Items unit gross and expenses total price should be equals to the expected price.'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[]
     */
    protected function getOrderItemsPrices(OrderTransfer $orderTransfer): array
    {
        $prices = [
            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 0,
            ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 0,
            ItemTransfer::UNIT_GROSS_PRICE => 0,
        ];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $prices[ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION] += $itemTransfer->getSumPriceToPayAggregation();
            $prices[ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION] += $itemTransfer->getUnitPriceToPayAggregation() * $itemTransfer->getQuantity();
            $prices[ItemTransfer::UNIT_GROSS_PRICE] += $itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity();
        }

        return $prices;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getOrderExpensesTotalPrice(OrderTransfer $orderTransfer): int
    {
        $totalPrice = 0;
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $totalPrice += $expenseTransfer->getSumGrossPrice();
        }

        return $totalPrice;
    }
}
