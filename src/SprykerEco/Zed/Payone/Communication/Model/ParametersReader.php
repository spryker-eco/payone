<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Model;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToStoreFacadeInterface;
use SprykerEco\Zed\Payone\PayoneConfig;

class ParametersReader implements ParametersReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToStoreFacadeInterface
     */
    protected PayoneToStoreFacadeInterface $storeFacade;

    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected PayoneConfig $payoneConfig;

    /**
     * @param \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToStoreFacadeInterface $storeFacade
     * @param \SprykerEco\Zed\Payone\PayoneConfig $payoneConfig
     */
    public function __construct(PayoneToStoreFacadeInterface $storeFacade, PayoneConfig $payoneConfig)
    {
        $this->storeFacade = $storeFacade;
        $this->payoneConfig = $payoneConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    public function getRequestStandardParameter(): PayoneStandardParameterTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $standardParameter = $this->payoneConfig->getRequestStandardParameter();
        $standardParameter->setCurrency(
            current($storeTransfer->getAvailableCurrencyIsoCodes()),
        );
        $standardParameter->setLanguage(
            current($storeTransfer->getCountries()),
        );

        return $standardParameter;
    }
}
