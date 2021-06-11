<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use Generated\Shared\Transfer\CreditCardCheckResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;

interface CreditCardCheckResponseMapperInterface extends ResponseMapperInterface
{
    /**
     * @const string NAME
     */
    public const NAME = 'CREDIT_CARD_CHECK';

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function getCreditCardCheckResponseTransfer(CreditCardCheckResponseContainer $responseContainer): CreditCardCheckResponseTransfer;
}
