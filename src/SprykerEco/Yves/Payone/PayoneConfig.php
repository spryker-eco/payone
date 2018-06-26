<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Plugin\Provider\PayoneControllerProvider;

class PayoneConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getStandardCheckoutEntryPoint()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL];
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->getYvesBaseUrl() . PayoneControllerProvider::EXPRESS_CHECKOUT_LOAD_DETAILS_PATH;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getYvesBaseUrl() . PayoneControllerProvider::EXPRESS_CHECKOUT_BACK_PATH;
    }

    /**
     * @return string
     */
    public function getFailureUrl()
    {
        return $this->getYvesBaseUrl() . PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE_PATH;
    }

    /**
     * @return string
     */
    public function getFailureProjectUrl()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EXPRESS_CHECKOUT_FAILURE_URL];
    }

    /**
     * @return string
     */
    public function getBackProjectUrl()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EXPRESS_CHECKOUT_BACK_URL];
    }

    /**
     * @return string
     */
    protected function getYvesBaseUrl()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::HOST_YVES];
    }
}
