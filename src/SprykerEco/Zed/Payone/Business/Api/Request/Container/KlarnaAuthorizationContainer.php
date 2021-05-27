<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class KlarnaAuthorizationContainer extends AbstractPayoneKlarnaAuthorizationContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer|null
     */
    public function getBusiness(): ?ContainerInterface
    {
        return $this->business;
    }
}
