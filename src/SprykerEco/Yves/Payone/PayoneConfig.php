<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Payone\PayoneConstants;

class PayoneConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getStandardCheckoutEntryPoint()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_STANDARD_CHECKOUT_ENTRY_POINT];
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_URL_SUCCESS];
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_URL_BACK];
    }

    /**
     * @return string
     */
    public function getFailureUrl()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_URL_FAILURE];
    }

    /**
     * @return string
     */
    public function getFailureProjectRoute()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EXPRESS_CHECKOUT_PROJECT_ROUTE_FAILURE];
    }

    /**
     * @return string
     */
    public function getBackProjectRoute()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EXPRESS_CHECKOUT_PROJECT_ROUTE_BACK];
    }
}
