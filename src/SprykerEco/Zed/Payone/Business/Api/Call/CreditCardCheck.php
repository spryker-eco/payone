<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Call;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer;

class CreditCardCheck extends AbstractCall implements CallInterface
{
    /**
     * @var string
     */
    private $storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;

    /**
     * @return void
     */
    public function setDoStoreCardData(): void
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;
    }

    /**
     * @return void
     */
    public function setDoNotStoreCardData(): void
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_NO;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer
     */
    public function mapCreditCardCheckData(): CreditCardCheckContainer
    {
        $container = new CreditCardCheckContainer();
        $this->applyStandardParameter($container);

        if ($container->getStoreCardData() === null) {
            $container->setStoreCardData($this->standardParameter->getStoreCardData());
        }

        $securityKey = $this->standardParameter->getKey();
        $hash = $this->hashGenerator->generateParamHash($container, $securityKey);

        $container->setHash($hash);

        return $container;
    }
}
