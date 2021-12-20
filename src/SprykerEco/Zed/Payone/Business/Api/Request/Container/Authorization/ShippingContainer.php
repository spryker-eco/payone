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
     * @param string $shipping_city
     *
     * @return void
     */
    public function setShippingCity(string $shipping_city): void
    {
        $this->shipping_city = $shipping_city;
    }

    /**
     * @return string
     */
    public function getShippingCity(): string
    {
        return $this->shipping_city;
    }

    /**
     * @param string $shipping_company
     *
     * @return void
     */
    public function setShippingCompany(string $shipping_company): void
    {
        $this->shipping_company = $shipping_company;
    }

    /**
     * @return string
     */
    public function getShippingCompany(): string
    {
        return $this->shipping_company;
    }

    /**
     * @param string $shipping_country
     *
     * @return void
     */
    public function setShippingCountry(string $shipping_country): void
    {
        $this->shipping_country = $shipping_country;
    }

    /**
     * @return string
     */
    public function getShippingCountry(): string
    {
        return $this->shipping_country;
    }

    /**
     * @param string $shipping_firstname
     *
     * @return void
     */
    public function setShippingFirstName(string $shipping_firstname): void
    {
        $this->shipping_firstname = $shipping_firstname;
    }

    /**
     * @return string
     */
    public function getShippingFirstName(): string
    {
        return $this->shipping_firstname;
    }

    /**
     * @param string $shipping_lastname
     *
     * @return void
     */
    public function setShippingLastName(string $shipping_lastname): void
    {
        $this->shipping_lastname = $shipping_lastname;
    }

    /**
     * @return string
     */
    public function getShippingLastName(): string
    {
        return $this->shipping_lastname;
    }

    /**
     * @param string $shipping_state
     *
     * @return void
     */
    public function setShippingState(string $shipping_state): void
    {
        $this->shipping_state = $shipping_state;
    }

    /**
     * @return string
     */
    public function getShippingState(): string
    {
        return $this->shipping_state;
    }

    /**
     * @param string $shipping_street
     *
     * @return void
     */
    public function setShippingStreet(string $shipping_street): void
    {
        $this->shipping_street = $shipping_street;
    }

    /**
     * @return string
     */
    public function getShippingStreet(): string
    {
        return $this->shipping_street;
    }

    /**
     * @param string $shipping_zip
     *
     * @return void
     */
    public function setShippingZip(string $shipping_zip): void
    {
        $this->shipping_zip = $shipping_zip;
    }

    /**
     * @return string
     */
    public function getShippingZip(): string
    {
        return $this->shipping_zip;
    }
}
