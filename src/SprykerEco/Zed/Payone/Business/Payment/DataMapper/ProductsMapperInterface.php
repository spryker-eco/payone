<?php

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface ProductsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $itemsContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function prepareProductItems($itemsContainer, AbstractRequestContainer $requestContainer): AbstractRequestContainer;
}
