<?php

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

class PayoneRequestProductDataMapper implements PayoneRequestProductDataMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface
     */
    protected $productsMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface
     */
    protected $discountMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface $productsMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface $shipmentMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface $discountMapper
     */
    public function __construct(
        ProductsMapperInterface $productsMapper,
        ShipmentMapperInterface $shipmentMapper,
        DiscountMapperInterface $discountMapper
    ) {
        $this->productsMapper = $productsMapper;
        $this->shipmentMapper = $shipmentMapper;
        $this->discountMapper = $discountMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $itemsContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function mapData($itemsContainer, AbstractRequestContainer $requestContainer): AbstractRequestContainer
    {
        $this->productsMapper->prepareProductItems($itemsContainer, $requestContainer);
        $this->shipmentMapper->prepareShipment($itemsContainer, $requestContainer);
        $this->discountMapper->prepareDiscount($itemsContainer, $requestContainer);

        return $requestContainer;
    }
}
