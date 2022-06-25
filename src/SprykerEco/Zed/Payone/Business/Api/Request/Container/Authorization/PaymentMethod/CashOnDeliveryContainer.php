<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class CashOnDeliveryContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string|null
     */
    protected $shippingprovider;

    /**
     * @param string|null $shippingprovider
     *
     * @return void
     */
    public function setShippingProvider(?string $shippingprovider = null): void
    {
        $this->shippingprovider = $shippingprovider;
    }

    /**
     * @return string|null
     */
    public function getShippingProvider(): ?string
    {
        return $this->shippingprovider;
    }
}
