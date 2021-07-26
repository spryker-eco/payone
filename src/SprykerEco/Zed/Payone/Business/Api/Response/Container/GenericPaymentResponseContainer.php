<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class GenericPaymentResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected $workorderid;

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
     * @var string
     */
    protected $shipping_state;

    /**
     * @var string
     */
    protected $shipping_country;

    /**
     * @var string
     */
    protected $shipping_addressaddition;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
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
     * @return string
     */
    public function getWorkOrderId()
    {
        return $this->workorderid;
    }

    /**
     * @param string $workOrderId
     *
     * @return void
     */
    public function setWorkOrderId(string $workOrderId)
    {
        $this->workorderid = $workOrderId;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirecturl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return void
     */
    public function setRedirectUrl(string $redirectUrl)
    {
        $this->redirecturl = $redirectUrl;
    }

    /**
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->shipping_firstname;
    }

    /**
     * @param string $shippingFirstName
     *
     * @return void
     */
    public function setShippingFirstname(string $shippingFirstName)
    {
        $this->shipping_firstname = $shippingFirstName;
    }

    /**
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->shipping_lastname;
    }

    /**
     * @param string $shippingLastName
     *
     * @return void
     */
    public function setShippingLastName(string $shippingLastName)
    {
        $this->shipping_lastname = $shippingLastName;
    }

    /**
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->shipping_company;
    }

    /**
     * @param string $shippingCompany
     *
     * @return void
     */
    public function setShippingCompany(string $shippingCompany)
    {
        $this->shipping_company = $shippingCompany;
    }

    /**
     * @return string
     */
    public function getShippingStreet()
    {
        return $this->shipping_street;
    }

    /**
     * @param string $shippingStreet
     *
     * @return void
     */
    public function setShippingStreet(string $shippingStreet)
    {
        $this->shipping_street = $shippingStreet;
    }

    /**
     * @return string
     */
    public function getShippingZip()
    {
        return $this->shipping_zip;
    }

    /**
     * @param string $shippingZip
     *
     * @return void
     */
    public function setShippingZip(string $shippingZip)
    {
        $this->shipping_zip = $shippingZip;
    }

    /**
     * @return string
     */
    public function getShippingCity()
    {
        return $this->shipping_city;
    }

    /**
     * @param string $shippingCity
     *
     * @return void
     */
    public function setShippingCity(string $shippingCity)
    {
        $this->shipping_city = $shippingCity;
    }

    /**
     * @return string
     */
    public function getShippingState()
    {
        return $this->shipping_state;
    }

    /**
     * @param string $shippingState
     *
     * @return void
     */
    public function setShippingState(string $shippingState)
    {
        $this->shipping_state = $shippingState;
    }

    /**
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->shipping_country;
    }

    /**
     * @param string $shippingCountry
     *
     * @return void
     */
    public function setShippingCountry(string $shippingCountry)
    {
        $this->shipping_country = $shippingCountry;
    }

    /**
     * @return string
     */
    public function getShippingAddressAddition()
    {
        return $this->shipping_addressaddition;
    }

    /**
     * @param string $shippingAddressAddition
     *
     * @return void
     */
    public function setShippingAddressAddition(string $shippingAddressAddition)
    {
        $this->shipping_addressaddition = $shippingAddressAddition;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email)
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
