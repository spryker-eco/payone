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
    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER_PAYONE = 'Payone';

    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER_DUMMY = 'Dummy';

    /**
     * @var \SprykerEcoTest\Zed\Payone\PayoneZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDistributePricesShouldReturnOrderTransferWithDistributedPrices(): void
    {
        foreach ($this->orderPriceForDistributionDataProvider() as $dataSet) {
            $this->distributePricesShouldReturnOrderTransferWithDistributedPrices(
                $dataSet['itemTransfers'],
                $dataSet['expenseTransfers'],
                $dataSet['paymentTransfers'],
                $dataSet['totalsTransfer'],
                $dataSet['expectedTotalPrice'],
            );
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $orderItemTransfers
     * @param array<\Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     * @param array<\Generated\Shared\Transfer\PaymentTransfer> $paymentTransfers
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param int $expectedTotalPrice
     *
     * @return void
     */
    protected function distributePricesShouldReturnOrderTransferWithDistributedPrices(
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
            'Grand total should be equals to the expected price.',
        );

        $this->assertSame(
            $expectedTotalPrice,
            $itemsPrices[ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION] + $expensesPrices,
            'Items and expenses total price should be equal to the expected price.',
        );

        $this->assertSame(
            $expectedTotalPrice,
            $itemsPrices[ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION] + $expensesPrices,
            'Items unit to aggregation and expenses total price should be equals to the expected price.',
        );

        $this->assertSame(
            $expectedTotalPrice,
            $itemsPrices[ItemTransfer::UNIT_GROSS_PRICE] + $expensesPrices,
            'Items unit gross and expenses total price should be equals to the expected price.',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<int>
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
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildItemTransfer(array $seedData = []): ItemTransfer
    {
        return (new ItemBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function buildExpenseTransfer(array $seedData = []): ExpenseTransfer
    {
        return (new ExpenseBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function buildPaymentTransfer(array $seedData = []): PaymentTransfer
    {
        return (new PaymentBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function buildTotalsTransfer(array $seedData = []): TotalsTransfer
    {
        return (new TotalsBuilder($seedData))->build();
    }

    /**
     * @return array<mixed>
     */
    public function orderPriceForDistributionDataProvider(): array
    {
        return [
            'order data without expenses' => $this->provideOrderDataWithoutExpenses(),
            'order data without items' => $this->provideOrderDataWithoutItems(),
            'order data with items and expenses' => $this->provideOrderDataWithItemsAndExpenses(),
            'order data with only payone payment' => $this->provideOrderDataWithOnlyPayonePayment(),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function provideOrderDataWithoutExpenses(): array
    {
        return [
            'itemTransfers' => [
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
            'expenseTransfers' => [],
            'paymentTransfers' => [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 100,
                ]),
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 150,
                ]),
            ],
            'totalsTransfer' => $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 250,
            ]),
            'expectedTotalPrice' => 100,
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function provideOrderDataWithoutItems(): array
    {
        return [
            'itemTransfers' => [],
            'expenseTransfers' => [
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
            'paymentTransfers' => [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 170,
                ]),
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 700,
                ]),
            ],
            'totalsTransfer' => $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 870,
            ]),
            'expectedTotalPrice' => 170,
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function provideOrderDataWithItemsAndExpenses(): array
    {
        return [
            'itemTransfers' => [
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 200,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 200,
                    ItemTransfer::UNIT_GROSS_PRICE => 200,
                    ItemTransfer::QUANTITY => 1,
                ]),
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 300,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 300,
                    ItemTransfer::UNIT_GROSS_PRICE => 300,
                    ItemTransfer::QUANTITY => 1,
                ]),
            ],
            'expenseTransfers' => [
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
            'paymentTransfers' => [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 260,
                ]),
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 1000,
                ]),
            ],
            'totalsTransfer' => $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 1260,
            ]),
            'expectedTotalPrice' => 260,
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function provideOrderDataWithOnlyPayonePayment(): array
    {
        return [
            'itemTransfers' => [
                $this->buildItemTransfer([
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 500,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 500,
                    ItemTransfer::UNIT_GROSS_PRICE => 500,
                    ItemTransfer::QUANTITY => 1,
                ]),
            ],
            'expenseTransfers' => [
                $this->buildExpenseTransfer([
                    ExpenseTransfer::SUM_GROSS_PRICE => 100,
                ]),
            ],
            'paymentTransfers' => [
                $this->buildPaymentTransfer([
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 600,
                ]),
            ],
            'totalsTransfer' => $this->buildTotalsTransfer([
                TotalsTransfer::GRAND_TOTAL => 600,
            ]),
            'expectedTotalPrice' => 600,
        ];
    }
}
