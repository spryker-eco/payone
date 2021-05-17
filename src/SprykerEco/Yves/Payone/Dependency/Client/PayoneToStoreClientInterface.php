<?php

namespace SprykerEco\Yves\Payone\Dependency\Client;

interface PayoneToStoreClientInterface
{
    /**
     * Specification:
     * - Retrieves the current Store as a transfer object.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();
}
