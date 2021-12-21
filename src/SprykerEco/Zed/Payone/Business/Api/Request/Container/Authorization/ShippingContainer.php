<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class ShippingContainer extends AbstractContainer
{
    /**
     * @var string
     */
    protected $shipping_firstname;

    /**
     * @var string
     */
    protected $shipping_lastname;

    /**
     * @var string
     */
    protected $shipping_company;

    /**
     * @var string
     */
    protected $shipping_street;

    /**
     * @var string
     */
    protected $shipping_zip;

    /**
     * @var string
     */
    protected $shipping_city;

    /**
     * ISO-3166-2 Subdivisions
     * only necessary for country US, CA, CN, JP, MX, BR, AR, ID, TH, IN
     *
     * @var string
     */
    protected $shipping_state;

    /**
     * Country (ISO-3166)
     *
     * @var string
     */
    protected $shipping_country;

    /**
     * @param string $shippingCity
     *
     * @return void
     */
    public function setShippingCity(string $shippingCity): void
    {
        $this->shipping_city = $shippingCity;
    }

    /**
     * @return string
     */
    public function getShippingCity(): string
    {
        return $this->shipping_city;
    }

    /**
     * @param string $shippingCompany
     *
     * @return void
     */
    public function setShippingCompany(string $shippingCompany): void
    {
        $this->shipping_company = $shippingCompany;
    }

    /**
     * @return string
     */
    public function getShippingCompany(): string
    {
        return $this->shipping_company;
    }

    /**
     * @param string $shippingCountry
     *
     * @return void
     */
    public function setShippingCountry(string $shippingCountry): void
    {
        $this->shipping_country = $shippingCountry;
    }

    /**
     * @return string
     */
    public function getShippingCountry(): string
    {
        return $this->shipping_country;
    }

    /**
     * @param string $shippingFirstname
     *
     * @return void
     */
    public function setShippingFirstName(string $shippingFirstname): void
    {
        $this->shipping_firstname = $shippingFirstname;
    }

    /**
     * @return string
     */
    public function getShippingFirstName(): string
    {
        return $this->shipping_firstname;
    }

    /**
     * @param string $shippingLastname
     *
     * @return void
     */
    public function setShippingLastName(string $shippingLastname): void
    {
        $this->shipping_lastname = $shippingLastname;
    }

    /**
     * @return string
     */
    public function getShippingLastName(): string
    {
        return $this->shipping_lastname;
    }

    /**
     * @param string $shippingState
     *
     * @return void
     */
    public function setShippingState(string $shippingState): void
    {
        $this->shipping_state = $shippingState;
    }

    /**
     * @return string
     */
    public function getShippingState(): string
    {
        return $this->shipping_state;
    }

    /**
     * @param string $shippingStreet
     *
     * @return void
     */
    public function setShippingStreet(string $shippingStreet): void
    {
        $this->shipping_street = $shippingStreet;
    }

    /**
     * @return string
     */
    public function getShippingStreet(): string
    {
        return $this->shipping_street;
    }

    /**
     * @param string $shippingZip
     *
     * @return void
     */
    public function setShippingZip(string $shippingZip): void
    {
        $this->shipping_zip = $shippingZip;
    }

    /**
     * @return string
     */
    public function getShippingZip(): string
    {
        return $this->shipping_zip;
    }
}
