<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;

class GenericPaymentContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_GENERICPAYMENT;
    /**
     * @var string
     */
    protected $clearingtype;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $narrative_text;

    /**
     * @var PaydataContainer
     */
    protected $paydata;

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
    protected $wallettype;

    /**
     * @var string
     */
    protected $successurl;

    /**
     * @var string
     */
    protected $errorurl;

    /**
     * @var string
     */
    protected $backurl;

    /**
     * @return string
     */
    public function getClearingType()
    {
        return $this->clearingtype;
    }

    /**
     * @param string $clearingType
     */
    public function setClearingType($clearingType)
    {
        $this->clearingtype = $clearingType;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getNarrativeText()
    {
        return $this->narrative_text;
    }

    /**
     * @param string $narrativeText
     */
    public function setNarrativeText($narrativeText)
    {
        $this->narrative_text = $narrativeText;
    }

    /**
     * @return PaydataContainer
     */
    public function getPaydata()
    {
        return $this->paydata;
    }

    /**
     * @param PaydataContainer $paydata
     */
    public function setPaydata($paydata)
    {
        $this->paydata = $paydata;
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
     */
    public function setWorkOrderId($workOrderId)
    {
        $this->workorderid = $workOrderId;
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
     */
    public function setShippingFirstName($shippingFirstName)
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
     */
    public function setShippingLastName($shippingLastName)
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
     */
    public function setShippingCompany($shippingCompany)
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
     */
    public function setShippingStreet($shippingStreet)
    {
        $this->shipping_street = $shippingStreet;
    }

    /**
     * @return int
     */
    public function getShippingZip()
    {
        return $this->shipping_zip;
    }

    /**
     * @param int $shippingZip
     */
    public function setShippingZip($shippingZip)
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
     */
    public function setShippingCity($shippingCity)
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
     */
    public function setShippingState($shippingState)
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
     */
    public function setShippingCountry($shippingCountry)
    {
        $this->shipping_country = $shippingCountry;
    }

    /**
     * @return string
     */
    public function getWalletType()
    {
        return $this->wallettype;
    }

    /**
     * @param string $walletType
     */
    public function setWalletType($walletType)
    {
        $this->wallettype = $walletType;
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->successurl;
    }

    /**
     * @param string $successUrl
     */
    public function setSuccessUrl($successUrl)
    {
        $this->successurl = $successUrl;
    }

    /**
     * @return string
     */
    public function getErrorUrl()
    {
        return $this->errorurl;
    }

    /**
     * @param string $errorUrl
     */
    public function setErrorUrl($errorUrl)
    {
        $this->errorurl = $errorUrl;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->backurl;
    }

    /**
     * @param string $backUrl
     */
    public function setBackUrl($backUrl)
    {
        $this->backurl = $backUrl;
    }

}
