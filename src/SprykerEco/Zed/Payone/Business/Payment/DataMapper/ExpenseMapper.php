<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

class ExpenseMapper implements ExpenseMapperInterface
{
    /**
     * @var string
     */
    protected const HANDLING_PRODUCT_DESCRIPTION = 'Handling';

    /**
     * @var int
     */
    protected const ZERRO_ITEM_TAX_RATE = 0;

    /**
     * @var int
     */
    protected const ONE_ITEM_AMOUNT = 1;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function mapExpenses(OrderTransfer $orderTransfer, AbstractRequestContainer $container): AbstractRequestContainer
    {
        $arrayIt = $container->getIt();
        $arrayId = $container->getId();
        $arrayPr = $container->getPr();
        $arrayNo = $container->getNo();
        $arrayDe = $container->getDe();
        $arrayVa = $container->getVa();

        $key = count($arrayId) + 1;

        foreach ($orderTransfer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_HANDLING;
            $arrayId[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_HANDLING;
            $arrayPr[$key] = $expense->getSumGrossPrice();
            $arrayNo[$key] = static::ONE_ITEM_AMOUNT;
            $arrayDe[$key] = static::HANDLING_PRODUCT_DESCRIPTION;
            $arrayVa[$key] = static::ZERRO_ITEM_TAX_RATE;
            $key++;
        }

        $container->setIt($arrayIt);
        $container->setId($arrayId);
        $container->setPr($arrayPr);
        $container->setNo($arrayNo);
        $container->setDe($arrayDe);
        $container->setVa($arrayVa);

        return $container;
    }
}
