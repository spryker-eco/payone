<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Distributor;

use ArrayObject;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\TotalsBuilder;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DummyAdapter;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Distributor
 * @group OrderPriceDistributorTest
 */
class OrderPriceDistributorTest extends AbstractBusinessTest
{
    protected const PAYMENT_PROVIDER_PAYONE = 'Payone';
    protected const PAYMENT_PROVIDER_DUMMY = 'Dummy';

    /**
     * @var \SprykerEcoTest\Zed\Payone\PayoneZedTester
     */
    protected $tester;

    /**
     * @dataProvider provideOrdersData
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param int $expectedTotalPrice
     *
     * @return void
     */
    public function testDistributePricesShouldReturnOrderTransferWithDistributedPrices(
        array $orderItemTransfers,
        array $expenseTransfers,
        array $paymentTransfers,
        TotalsTransfer $totalsTransfer,
        int $expectedTotalPrice
    ): void {
        // Arrange
        $adapter = new DummyAdapter('{}');
        $orderTransfer = $this->orderTransfer
            ->setItems(new ArrayObject($orderItemTransfers))
            ->setExpenses(new ArrayObject($expenseTransfers))
            ->setPayments(new ArrayObject($paymentTransfers))
            ->setTotals($totalsTransfer);

        // Act
        $distributedPriceOrderTransfer = $this->createBusinessFactoryMock($adapter)
            ->createOrderPriceDistributor()
            ->distributeOrderPrice($orderTransfer);

        // Assert
        $itemsPrices = $this->getOrderItemsPrices($distributedPriceOrderTransfer);
        $expensesPrices = $this->getOrderExpensesTotalPrice($distributedPriceOrderTransfer);

        $this->assertSame(
            $expectedTotalPrice,
            $distributedPriceOrderTransfer->getTotals()->getGrandTotal(),
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

    /**
     * @param mixed[] $seedData
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildItemTransfer(array $seedData = []): ItemTransfer
    {
        return (new ItemBuilder($seedData))->build();
    }

    /**
     * @param mixed[] $seedData
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function buildExpenseTransfer(array $seedData = []): ExpenseTransfer
    {
        return (new ExpenseBuilder($seedData))->build();
    }

    /**
     * @param mixed[] $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function buildPaymentTransfer(array $seedData = []): PaymentTransfer
    {
        return (new PaymentBuilder($seedData))->build();
    }

    /**
     * @param mixed[] $seedData
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function buildTotalsTransfer(array $seedData = []): TotalsTransfer
    {
        return (new TotalsBuilder($seedData))->build();
    }

    /**
     * @return mixed[]
     */
    public function provideOrdersData(): array
    {
        return [
            $this->provideOrdersDataWithoutExpenses(),
            $this->provideOrdersDataWithoutItems(),
            $this->provideOrdersDataWithItemsAndExpenses(),
            $this->provideOrdersDataWithOnlyPayonePayment(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function provideOrdersDataWithoutExpenses()
    {
        return [
            [
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 100,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 100,
                    ItemTransfer::UNIT_GROSS_PRICE => 100,
                    ItemTransfer::QUANTITY => 1,
                ]),
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 150,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 150,
                    ItemTransfer::UNIT_GROSS_PRICE => 150,
                    ItemTransfer::QUANTITY => 1,
                ]),
            ],
            [],
            [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 100,
                ]),
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 150,
                ]),
            ],
            $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 250,
            ]),
            100,
        ];
    }

    /**
     * @return mixed[]
     */
    protected function provideOrdersDataWithoutItems()
    {
        return [
            [],
            [
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 450,
                ]),
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 120,
                ]),
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 300,
                ]),
            ],
            [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 170,
                ]),
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 700,
                ]),
            ],
            $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 870,
            ]),
            170,
        ];
    }

    /**
     * @return mixed[]
     */
    protected function provideOrdersDataWithItemsAndExpenses()
    {
        return [
            [
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 200,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 100,
                    ItemTransfer::UNIT_GROSS_PRICE => 100,
                    ItemTransfer::QUANTITY => 2,
                ]),
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 300,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 300,
                    ItemTransfer::UNIT_GROSS_PRICE => 300,
                    ItemTransfer::QUANTITY => 1,
                ]),
            ],
            [
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 100,
                ]),
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 570,
                ]),
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 90,
                ]),
            ],
            [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 160,
                ]),
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 1000,
                ]),
            ],
            $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 1260,
            ]),
            160,
        ];
    }

    /**
     * @return mixed[]
     */
    protected function provideOrdersDataWithOnlyPayonePayment()
    {
        return [
            [
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 500,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 500,
                    ItemTransfer::UNIT_GROSS_PRICE => 500,
                    ItemTransfer::QUANTITY => 1,
                ]),
            ],
            [
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 100,
                ]),
            ],
            [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 600,
                ]),
            ],
            $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 600,
            ]),
            600,
        ];
    }
}
