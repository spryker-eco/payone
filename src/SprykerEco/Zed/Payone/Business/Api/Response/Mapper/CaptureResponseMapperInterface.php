<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;

interface CaptureResponseMapperInterface extends ResponseMapperInterface
{

    /**
     * @const string NAME
     */
    const NAME = 'CAPTURE';

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function getCaptureResponseTransfer(CaptureResponseContainer $responseContainer);

}
