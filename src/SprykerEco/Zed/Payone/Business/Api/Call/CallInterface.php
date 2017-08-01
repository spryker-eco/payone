<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Call;

interface CallInterface
{

    /**
     * @return void
     */
    public function setDoStoreCardData();

    /**
     * @return void
     */
    public function setDoNotStoreCardData();

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer
     */
    public function mapCreditCardCheckData();

}
