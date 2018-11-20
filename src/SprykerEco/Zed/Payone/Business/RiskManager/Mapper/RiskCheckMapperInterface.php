<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\RiskManager\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;

interface RiskCheckMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function mapAddressCheck(QuoteTransfer $quoteTransfer): ContainerInterface;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function mapConsumerScoreCheck(QuoteTransfer $quoteTransfer): ContainerInterface;
}
