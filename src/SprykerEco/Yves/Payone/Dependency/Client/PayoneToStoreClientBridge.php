<?php

namespace SprykerEco\Yves\Payone\Dependency\Client;

use Spryker\Client\Store\StoreClientInterface;

class PayoneToStoreClientBridge implements PayoneToStoreClientInterface
{
    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected $storeClient;

    public function __construct(StoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->storeClient->getCurrentStore();
    }
}
