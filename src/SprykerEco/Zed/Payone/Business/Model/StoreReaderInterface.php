<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Model;

interface StoreReaderInterface
{
    /**
     * @return string
     */
    public function getDefaultStoreCountry(): string;
}
