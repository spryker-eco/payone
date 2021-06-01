<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Call;

use SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainerInterface;

interface CreditCardCheckInterface extends CallInterface
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
     * @return \SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainerInterface
     */
    public function mapCreditCardCheckData(): CreditCardCheckContainerInterface;
}
