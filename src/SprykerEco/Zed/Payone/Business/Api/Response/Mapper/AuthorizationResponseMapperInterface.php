<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;

interface AuthorizationResponseMapperInterface extends ResponseMapperInterface
{

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function getAuthorizationResponseTransfer(AuthorizationResponseContainer $responseContainer);

}