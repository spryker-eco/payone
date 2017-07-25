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
    protected $clearingType;

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
    protected $narrativeText;

    /**
     * @var PaydataContainer
     */
    protected $paydata;

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
    protected $walletType;

    /**
     * @var string
     */
    protected $successUrl;

    /**
     * @var string
     */
    protected $errorUrl;

    /**
     * @var string
     */
    protected $backUrl;

    /**
     * @return string
     */
    public function getClearingType()
    {
        return $this->clearingType;
    }

    /**
     * @param string $clearingType
     */
    public function setClearingType($clearingType)
    {
        $this->clearingType = $clearingType;
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
        return $this->narrativeText;
    }

    /**
     * @param string $narrativeText
     */
    public function setNarrativeText($narrativeText)
    {
        $this->narrativeText = $narrativeText;
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
        return $this->workOrderId;
    }

    /**
     * @param string $workOrderId
     */
    public function setWorkOrderId($workOrderId)
    {
        $this->workOrderId = $workOrderId;
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
    public function setShippingFirstName($shippingFirstName)
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
    public function setShippingLastName($shippingLastName)
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
    public function setShippingCompany($shippingCompany)
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
    public function setShippingStreet($shippingStreet)
    {
        $this->shippingStreet = $shippingStreet;
    }

    /**
     * @return int
     */
    public function getShippingZip()
    {
        return $this->shippingZip;
    }

    /**
     * @param int $shippingZip
     */
    public function setShippingZip($shippingZip)
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
    public function setShippingCity($shippingCity)
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
    public function setShippingState($shippingState)
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
    public function setShippingCountry($shippingCountry)
    {
        $this->shippingCountry = $shippingCountry;
    }

    /**
     * @return string
     */
    public function getWalletType()
    {
        return $this->walletType;
    }

    /**
     * @param string $walletType
     */
    public function setWalletType($walletType)
    {
        $this->walletType = $walletType;
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->successUrl;
    }

    /**
     * @param string $successUrl
     */
    public function setSuccessUrl($successUrl)
    {
        $this->successUrl = $successUrl;
    }

    /**
     * @return string
     */
    public function getErrorUrl()
    {
        return $this->errorUrl;
    }

    /**
     * @param string $errorUrl
     */
    public function setErrorUrl($errorUrl)
    {
        $this->errorUrl = $errorUrl;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->backUrl;
    }

    /**
     * @param string $backUrl
     */
    public function setBackUrl($backUrl)
    {
        $this->backUrl = $backUrl;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPreparedKey($key)
    {
        $keyMap = [
            'clearingType'      => 'clearingtype',
            'narrativeText'     => 'narrative_text',
            'workOrderId'       => 'workorderid',
            'shippingFirstName' => 'shipping_firstname',
            'shippingLastName'  => 'shipping_lastname',
            'shippingCompany'   => 'shipping_company',
            'shippingStreet'    => 'shipping_street',
            'shippingZip'       => 'shipping_zip',
            'shippingCity'      => 'shipping_city',
            'shippingState'     => 'shipping_state',
            'shippingCountry'   => 'shipping_country',
            'walletType'        => 'wallettype',
            'successUrl'        => 'successurl',
            'errorUrl'          => 'errorurl',
            'backUrl'           => 'backurl'
        ];
        return isset($keyMap[$key]) ? $keyMap[$key] : $key;
    }

}
