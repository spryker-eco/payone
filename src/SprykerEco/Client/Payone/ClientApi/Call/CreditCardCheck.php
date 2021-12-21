<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Call;

use SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainer;
use SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainerInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;

class CreditCardCheck extends AbstractCall implements CreditCardCheckInterface
{
    /**
     * @var string
     */
    protected $storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;

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
     * @return \SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainerInterface
     */
    public function mapCreditCardCheckData(): CreditCardCheckContainerInterface
    {
        $container = new CreditCardCheckContainer($this->utilEncodingService);
        $this->applyStandardParameter($container);
        $securityKey = (string)$this->standardParameter->getKey();
        $hash = $this->hashGenerator->generateHash($container, $securityKey);

        $container->setHash($hash);

        return $container;
    }
}
