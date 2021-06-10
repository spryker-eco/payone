<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use Generated\Shared\Transfer\RefundResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;

interface RefundResponseMapperInterface extends ResponseMapperInterface
{
    /**
     * @const string NAME
     */
    public const NAME = 'REFUND';

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function getRefundResponseTransfer(RefundResponseContainer $responseContainer): RefundResponseTransfer;
}
