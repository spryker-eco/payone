<?php

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface OrderHandlingMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function prepareOrderHandling(OrderTransfer $orderTransfer, AbstractRequestContainer $container): AbstractRequestContainer;
}
