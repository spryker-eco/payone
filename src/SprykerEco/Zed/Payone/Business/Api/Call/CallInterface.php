<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Call;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer;

interface CallInterface
{
    /**
     * @return void
     */
    public function setDoStoreCardData(): void;

    /**
     * @return void
     */
    public function setDoNotStoreCardData(): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer
     */
    public function mapCreditCardCheckData(): CreditCardCheckContainer;
}
