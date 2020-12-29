<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\DataProvider;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

trait PayoneDistributePricesDataProvider
{
    /**
     * @return array
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
     * @return array
     */
    protected function provideOrdersDataWithoutExpenses()
    {
        return [
            [
                [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 100,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 100,
                    ItemTransfer::UNIT_GROSS_PRICE => 100,
                    ItemTransfer::QUANTITY => 1,
                ],
                [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 150,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 150,
                    ItemTransfer::UNIT_GROSS_PRICE => 150,
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
            [],
            [
                [
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 100,
                ],
                [
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 150,
                ],
            ],
            [
                TotalsTransfer::GRAND_TOTAL => 250,
            ],
            100,
        ];
    }

    /**
     * @return array
     */
    protected function provideOrdersDataWithoutItems()
    {
        return [
            [],
            [
                [
                    ExpenseTransfer::SUM_GROSS_PRICE => 450,
                ],
                [
                    ExpenseTransfer::SUM_GROSS_PRICE => 120,
                ],
                [
                    ExpenseTransfer::SUM_GROSS_PRICE => 300,
                ],
            ],
            [
                [
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 170,
                ],
                [
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 700,
                ],
            ],
            [
                TotalsTransfer::GRAND_TOTAL => 870,
            ],
            170,
        ];
    }

    /**
     * @return array
     */
    protected function provideOrdersDataWithItemsAndExpenses()
    {
        return [
            [
                [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 200,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 100,
                    ItemTransfer::UNIT_GROSS_PRICE => 100,
                    ItemTransfer::QUANTITY => 2,
                ],
                [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 300,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 300,
                    ItemTransfer::UNIT_GROSS_PRICE => 300,
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
            [
                [
                    ExpenseTransfer::SUM_GROSS_PRICE => 100,
                ],
                [
                    ExpenseTransfer::SUM_GROSS_PRICE => 570,
                ],
                [
                    ExpenseTransfer::SUM_GROSS_PRICE => 90,
                ],
            ],
            [
                [
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 160,
                ],
                [
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_DUMMY,
                    PaymentTransfer::AMOUNT => 1000,
                ],
            ],
            [
                TotalsTransfer::GRAND_TOTAL => 1260,
            ],
            160,
        ];
    }

    /**
     * @return array
     */
    protected function provideOrdersDataWithOnlyPayonePayment()
    {
        return [
            [
                [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 500,
                    ItemTransfer::UNIT_PRICE_TO_PAY_AGGREGATION => 500,
                    ItemTransfer::UNIT_GROSS_PRICE => 500,
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
            [
                [
                    ExpenseTransfer::SUM_GROSS_PRICE => 100,
                ],
            ],
            [
                [
                    PaymentTransfer::PAYMENT_PROVIDER => static::PAYMENT_PROVIDER_PAYONE,
                    PaymentTransfer::AMOUNT => 600,
                ],
            ],
            [
                TotalsTransfer::GRAND_TOTAL => 600,
            ],
            600,
        ];
    }
}
