<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Model;

use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToStoreFacadeInterface;

class StoreReader implements StoreReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToStoreFacadeInterface
     */
    protected PayoneToStoreFacadeInterface $storeFacade;

    /**
     * @param \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToStoreFacadeInterface $storeClient
     */
    public function __construct(PayoneToStoreClientInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return string
     */
    public function getDefaultStoreCountry(): string
    {
        return current($this->storeFacade->getCurrentStore()->getCountries());
    }
}
