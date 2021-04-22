<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

class ProductsMapper
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $itemsContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function prepareProductItems($itemsContainer, AbstractRequestContainer $requestContainer): AbstractRequestContainer
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
            $arrayVa[$key] = (int)$itemTransfer->getTaxRate();
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
}
