<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Call;

use SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainer;
use SprykerEco\Shared\Payone\PayoneApiConstants;

class CreditCardCheck extends AbstractCall
{

    /**
     * @var string
     */
    private $storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;

    /**
     * @return void
     */
    public function setDoStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_YES;
    }

    /**
     * @return void
     */
    public function setDoNotStoreCardData()
    {
        $this->storeCardData = PayoneApiConstants::STORE_CARD_DATA_NO;
    }

    /**
     * @return \SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainer
     */
    public function mapCreditCardCheckData()
    {
        $container = new CreditCardCheckContainer($this->utilEncodingService);
        $this->applyStandardParameter($container);
        $securityKey = $this->standardParameter->getKey();
        $hash = $this->hashGenerator->generateHash($container, $securityKey);

        $container->setHash($hash);

        return $container;
    }

}
