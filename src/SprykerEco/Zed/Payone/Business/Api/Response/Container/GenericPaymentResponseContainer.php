<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class GenericPaymentResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string|null
     */
    protected $workorderid;

    /**
     * @var string|null
     */
    protected $shipping_firstname;

    /**
     * @var string|null
     */
    protected $shipping_lastname;

    /**
     * @var string|null
     */
    protected $shipping_company;

    /**
     * @var string|null
     */
    protected $shipping_street;

    /**
     * @var string|null
     */
    protected $shipping_zip;

    /**
     * @var string|null
     */
    protected $shipping_city;

    /**
     * @var string|null
     */
    protected $shipping_state;

    /**
     * @var string|null
     */
    protected $shipping_country;

    /**
     * @var string|null
     */
    protected $shipping_addressaddition;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $redirecturl;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * @return string|null
     */
    public function getWorkOrderId(): ?string
    {
        return $this->workorderid;
    }

    /**
     * @param string $workOrderId
     *
     * @return void
     */
    public function setWorkOrderId(string $workOrderId): void
    {
        $this->workorderid = $workOrderId;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->redirecturl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return void
     */
    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirecturl = $redirectUrl;
    }

    /**
     * @return string|null
     */
    public function getShippingFirstName(): ?string
    {
        return $this->shipping_firstname;
    }

    /**
     * @param string $shippingFirstName
     *
     * @return void
     */
    public function setShippingFirstname(string $shippingFirstName): void
    {
        $this->shipping_firstname = $shippingFirstName;
    }

    /**
     * @return string|null
     */
    public function getShippingLastName(): ?string
    {
        return $this->shipping_lastname;
    }

    /**
     * @param string $shippingLastName
     *
     * @return void
     */
    public function setShippingLastName(string $shippingLastName): void
    {
        $this->shipping_lastname = $shippingLastName;
    }

    /**
     * @return string|null
     */
    public function getShippingCompany(): ?string
    {
        return $this->shipping_company;
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
     * @return string|null
     */
    public function getShippingStreet(): ?string
    {
        return $this->shipping_street;
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
     * @return string|null
     */
    public function getShippingZip(): ?string
    {
        return $this->shipping_zip;
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
     * @return string|null
     */
    public function getShippingCity(): ?string
    {
        return $this->shipping_city;
    }

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
     * @return string|null
     */
    public function getShippingState(): ?string
    {
        return $this->shipping_state;
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
     * @return string|null
     */
    public function getShippingCountry(): ?string
    {
        return $this->shipping_country;
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
     * @return string|null
     */
    public function getShippingAddressAddition(): ?string
    {
        return $this->shipping_addressaddition;
    }

    /**
     * @param string $shippingAddressAddition
     *
     * @return void
     */
    public function setShippingAddressAddition(string $shippingAddressAddition): void
    {
        $this->shipping_addressaddition = $shippingAddressAddition;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPreparedKey(string $key): string
    {
        $key = preg_replace('/add_paydata\[(.*)\]/', '$1', $key);

        return ucwords(str_replace('_', ' ', $key));
    }
}
