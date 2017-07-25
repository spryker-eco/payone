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
    protected $workOrderId;

    /**
     * @var string
     */
    protected $shippingFirstName;

    /**
     * @var string
     */
    protected $shippingLastName;

    /**
     * @var string
     */
    protected $shippingCompany;

    /**
     * @var string
     */
    protected $shippingStreet;

    /**
     * @var string
     */
    protected $shippingZip;

    /**
     * @var string
     */
    protected $shippingCity;

    /**
     * @var string
     */
    protected $shippingState;

    /**
     * @var string
     */
    protected $shippingCountry;

    /**
     * @var string
     */
    protected $shippingAddressAddition;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $redirectUrl;

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
        return $this->workOrderId;
    }

    /**
     * @param string $workOrderId
     *
     * @return void
     */
    public function setWorkOrderId(string $workOrderId)
    {
        $this->workOrderId = $workOrderId;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return void
     */
    public function setRedirectUrl(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->shippingFirstName;
    }

    /**
     * @param string $shippingFirstName
     */
    public function setShippingFirstname(string $shippingFirstName)
    {
        $this->shippingFirstName = $shippingFirstName;
    }

    /**
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->shippingLastName;
    }

    /**
     * @param string $shippingLastName
     */
    public function setShippingLastName(string $shippingLastName)
    {
        $this->shippingLastName = $shippingLastName;
    }

    /**
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->shippingCompany;
    }

    /**
     * @param string $shippingCompany
     */
    public function setShippingCompany(string $shippingCompany)
    {
        $this->shippingCompany = $shippingCompany;
    }

    /**
     * @return string
     */
    public function getShippingStreet()
    {
        return $this->shippingStreet;
    }

    /**
     * @param string $shippingStreet
     */
    public function setShippingStreet(string $shippingStreet)
    {
        $this->shippingStreet = $shippingStreet;
    }

    /**
     * @return string
     */
    public function getShippingZip()
    {
        return $this->shippingZip;
    }

    /**
     * @param string $shippingZip
     */
    public function setShippingZip(string $shippingZip)
    {
        $this->shippingZip = $shippingZip;
    }

    /**
     * @return string
     */
    public function getShippingCity()
    {
        return $this->shippingCity;
    }

    /**
     * @param string $shippingCity
     */
    public function setShippingCity(string $shippingCity)
    {
        $this->shippingCity = $shippingCity;
    }

    /**
     * @return string
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * @param string $shippingState
     */
    public function setShippingState(string $shippingState)
    {
        $this->shippingState = $shippingState;
    }

    /**
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->shippingCountry;
    }

    /**
     * @param string $shippingCountry
     */
    public function setShippingCountry(string $shippingCountry)
    {
        $this->shippingCountry = $shippingCountry;
    }

    /**
     * @return string
     */
    public function getShippingAddressAddition()
    {
        return $this->shippingAddressAddition;
    }

    /**
     * @param string $shippingAddressaddition
     */
    public function setShippingAddressAddition(string $shippingAddressAddition)
    {
        $this->shippingAddressAddition = $shippingAddressAddition;
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
    protected function getPreparedKey($key)
    {
        $key = preg_replace("/add_paydata\[(.*)\]/", "$1", $key);
        return ucwords(str_replace('_', ' ', $key));
    }

}