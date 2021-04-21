<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;

class KlarnaGenericPaymentContainer extends AbstractRequestContainer
{
    /**
     * @var string|null
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_GENERICPAYMENT;

    /**
     * @var string|null
     */
    protected $clearingtype;

    /**
     * @var int|null
     */
    protected $amount;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer|null
     */
    protected $paydata;

    /**
     * @var string|null
     */
    protected $workorderid;

    /**
     * @var string|null
     */
    protected $firstname;

    /**
     * @var string|null
     */
    protected $lastname;

    /**
     * @var string|null
     */
    protected $street;

    /**
     * @var string|null
     */
    protected $zip;

    /**
     * @var string|null
     */
    protected $city;

    /**
     * @var string|null
     */
    protected $country;

    /**
     * @var string|null
     */
    protected $financingtype;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer|null
     */
    protected $personalData;

    /**
     * @return string|null
     */
    public function getClearingType(): ?string
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
     * @return int|null
     */
    public function getAmount(): ?int
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
     * @return string|null
     */
    public function getCurrency(): ?string
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
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstName
     *
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstname = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastName
     *
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->lastname = $lastName;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return void
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return int|null
     */
    public function getZip(): ?int
    {
        return $this->zip;
    }

    /**
     * @param int $zip
     *
     * @return void
     */
    public function setShippingZip(int $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return void
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getFinancingType(): ?string
    {
        return $this->financingtype;
    }

    /**
     * @param string $financingType
     *
     * @return void
     */
    public function setFinancingType(string $financingType): void
    {
        $this->financingtype = $financingType;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer|null
     */
    public function getPaydata(): ?ContainerInterface
    {
        return $this->paydata;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer $payData
     *
     * @return void
     */
    public function setPaydata(PaydataContainer $payData): void
    {
        $this->paydata = $payData;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalData
     *
     * @return void
     */
    public function setPersonalData(PersonalContainer $personalData): void
    {
        $this->personalData = $personalData;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer|null
     */
    public function getPersonalData(): ?ContainerInterface
    {
        return $this->personalData;
    }
}
