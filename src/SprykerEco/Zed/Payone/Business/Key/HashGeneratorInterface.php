<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Key;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface HashGeneratorInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateParamHash(AbstractRequestContainer $request, $securityKey);
}
