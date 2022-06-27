<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

class PayoneRequestProductDataMapper implements PayoneRequestProductDataMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapperInterface
     */
    protected $productMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface
     */
    protected $discountMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapperInterface $productMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface $shipmentMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface $discountMapper
     */
    public function __construct(
        ProductMapperInterface $productMapper,
        ShipmentMapperInterface $shipmentMapper,
        DiscountMapperInterface $discountMapper
    ) {
        $this->productMapper = $productMapper;
        $this->shipmentMapper = $shipmentMapper;
        $this->discountMapper = $discountMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $itemsContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function mapProductData($itemsContainer, AbstractRequestContainer $requestContainer): AbstractRequestContainer
    {
        $this->productMapper->mapProductItems($itemsContainer, $requestContainer);
        $this->shipmentMapper->mapShipment($itemsContainer, $requestContainer);
        $this->discountMapper->mapDiscounts($itemsContainer, $requestContainer);

        return $requestContainer;
    }
}
