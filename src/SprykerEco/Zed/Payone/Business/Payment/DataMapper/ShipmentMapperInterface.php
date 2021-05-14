<?php

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $shipmentContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function prepareShipment($shipmentContainer, AbstractRequestContainer $container): AbstractRequestContainer;
}
