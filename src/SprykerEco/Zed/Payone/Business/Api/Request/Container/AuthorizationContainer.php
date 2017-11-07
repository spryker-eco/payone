<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer;

class AuthorizationContainer extends AbstractAuthorizationContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer
     */
    protected $business;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer $business
     *
     * @return $this
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
