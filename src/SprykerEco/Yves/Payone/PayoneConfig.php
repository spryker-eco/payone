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
     * @var array
     */
    protected const PAYONE_BANCONTACT_AVAILABLE_COUNTRIES = [
        'BE' => 'Belgium',
    ];

    /**
     * @api
     *
     * @return array Countries
     */
    public function getPayOneBancontactAvailableCountries(): array
    {
        return static::PAYONE_BANCONTACT_AVAILABLE_COUNTRIES;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStandardCheckoutEntryPoint(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->getYvesBaseUrl() . PayoneControllerProvider::EXPRESS_CHECKOUT_LOAD_DETAILS_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getYvesBaseUrl() . PayoneControllerProvider::EXPRESS_CHECKOUT_BACK_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFailureUrl(): string
    {
        return $this->getYvesBaseUrl() . PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFailureProjectUrl(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EXPRESS_CHECKOUT_FAILURE_URL];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBackProjectUrl(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EXPRESS_CHECKOUT_BACK_URL];
    }

    /**
     * @return string
     */
    protected function getYvesBaseUrl(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::HOST_YVES];
    }
}
