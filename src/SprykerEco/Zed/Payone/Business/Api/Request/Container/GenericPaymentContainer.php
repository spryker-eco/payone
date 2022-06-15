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
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer
     */
    protected $paydata;

    /**
     * @var string|null
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
     * @var int
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
    public function getClearingType(): string
    {
        return $this->clearingtype;
    }

    /**
     * @param string $clearingType
     *
     * @return void
     */
    public function setClearingType(string $clearingType): void
    {
        $this->clearingtype = $clearingType;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return void
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getNarrativeText(): string
    {
        return $this->narrative_text;
    }

    /**
     * @param string $narrativeText
     *
     * @return void
     */
    public function setNarrativeText(string $narrativeText): void
    {
        $this->narrative_text = $narrativeText;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer
     */
    public function getPaydata(): PaydataContainer
    {
        return $this->paydata;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer $paydata
     *
     * @return void
     */
    public function setPaydata(PaydataContainer $paydata): void
    {
        $this->paydata = $paydata;
    }

    /**
     * @return string|null
     */
    public function getWorkOrderId(): ?string
    {
        return $this->workorderid;
    }

    /**
     * @param string|null $workOrderId
     *
     * @return void
     */
    public function setWorkOrderId(?string $workOrderId = null): void
    {
        $this->workorderid = $workOrderId;
    }

    /**
     * @return string
     */
    public function getShippingFirstName(): string
    {
        return $this->shipping_firstname;
    }

    /**
     * @param string $shippingFirstName
     *
     * @return void
     */
    public function setShippingFirstName(string $shippingFirstName): void
    {
        $this->shipping_firstname = $shippingFirstName;
    }

    /**
     * @return string
     */
    public function getShippingLastName(): string
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
     * @return string
     */
    public function getShippingCompany(): string
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
     * @return string
     */
    public function getShippingStreet(): string
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
     * @return int
     */
    public function getShippingZip(): int
    {
        return $this->shipping_zip;
    }

    /**
     * @param int $shippingZip
     *
     * @return void
     */
    public function setShippingZip(int $shippingZip): void
    {
        $this->shipping_zip = $shippingZip;
    }

    /**
     * @return string
     */
    public function getShippingCity(): string
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
     * @return string
     */
    public function getShippingState(): string
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
     * @return string
     */
    public function getShippingCountry(): string
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
     * @return string
     */
    public function getWalletType(): string
    {
        return $this->wallettype;
    }

    /**
     * @param string $walletType
     *
     * @return void
     */
    public function setWalletType(string $walletType): void
    {
        $this->wallettype = $walletType;
    }
}
