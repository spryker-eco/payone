<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer;

class CreditCardPseudoContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string|null
     */
    protected $pseudocardpan;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer|null
     */
    protected $threedsecure;

    /**
     * @param string $pseudoCardPan
     *
     * @return void
     */
    public function setPseudoCardPan(string $pseudoCardPan): void
    {
        $this->pseudocardpan = $pseudoCardPan;
    }

    /**
     * @return string|null
     */
    public function getPseudoCardPan(): ?string
    {
        return $this->pseudocardpan;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer $threeDSecure
     *
     * @return void
     */
    public function setThreeDSecure(ThreeDSecureContainer $threeDSecure): void
    {
        $this->threedsecure = $threeDSecure;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer|null
     */
    public function getThreeDSecure(): ?ThreeDSecureContainer
    {
        return $this->threedsecure;
    }
}
