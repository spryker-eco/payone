<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\DecimalObject\Decimal;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

class ProductMapper implements ProductMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $itemsContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function mapProductItems($itemsContainer, AbstractRequestContainer $requestContainer): AbstractRequestContainer
    {
        $arrayIt = [];
        $arrayId = [];
        $arrayPr = [];
        $arrayNo = [];
        $arrayDe = [];
        $arrayVa = [];

        $key = 1;

        foreach ($itemsContainer->getItems() as $itemTransfer) {
            $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS;
            $arrayId[$key] = $itemTransfer->getSku();
            $arrayPr[$key] = $itemTransfer->getUnitGrossPrice();
            $arrayNo[$key] = $itemTransfer->getQuantity();
            $arrayDe[$key] = $itemTransfer->getName();
            $arrayVa[$key] = $this->getTaxRateFromItemTransfer($itemTransfer);
            $key++;
        }

        $requestContainer->setIt($arrayIt);
        $requestContainer->setId($arrayId);
        $requestContainer->setPr($arrayPr);
        $requestContainer->setNo($arrayNo);
        $requestContainer->setDe($arrayDe);
        $requestContainer->setVa($arrayVa);

        return $requestContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getTaxRateFromItemTransfer(ItemTransfer $itemTransfer): int
    {
        /** @var \Spryker\DecimalObject\Decimal|float|null $taxRateValue */
        $taxRateValue = $itemTransfer->getTaxRate();
        if ($taxRateValue instanceof Decimal) {
            return $taxRateValue->toInt();
        }

        return (int)$taxRateValue;
    }
}
