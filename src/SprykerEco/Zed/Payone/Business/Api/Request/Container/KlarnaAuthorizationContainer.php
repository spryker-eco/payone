<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;

class KlarnaAuthorizationContainer extends AbstractPayoneKlarnaAuthorizationContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer|null
     */
    protected $business;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer $business
     *
     * @return void
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer|null
     */
    public function getBusiness(): ?ContainerInterface
    {
        return $this->business;
    }
}
