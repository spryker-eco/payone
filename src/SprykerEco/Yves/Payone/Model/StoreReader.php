<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Model;

use SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface;

class StoreReader implements StoreReaderInterface
{
    /**
     * @var \SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface
     */
    protected PayoneToStoreClientInterface $storeClient;

    /**
     * @param \SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface $storeClient
     */
    public function __construct(PayoneToStoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @return string
     */
    public function getDefaultStoreCountry(): string
    {
        return current($this->storeClient->getCurrentStore()->getCountries());
    }
}
