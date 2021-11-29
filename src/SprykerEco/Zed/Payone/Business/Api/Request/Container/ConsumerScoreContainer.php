<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class ConsumerScoreContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_CONSUMERSCORE;

    /**
     * @var int
     */
    protected $aid;

    /**
     * @var string
     */
    protected $addresschecktype;

    /**
     * @var string
     */
    protected $consumerscoretype;

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $streetname;

    /**
     * @var string
     */
    protected $streetnumber;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $birthday;

    /**
     * @var string
     */
    protected $telephonenumber;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @param string $addresschecktype
     *
     * @return void
     */
    public function setAddressCheckType(string $addresschecktype): void
    {
        $this->addresschecktype = $addresschecktype;
    }

    /**
     * @return string
     */
    public function getAddressCheckType(): string
    {
        return $this->addresschecktype;
    }

    /**
     * @param string $birthday
     *
     * @return void
     */
    public function setBirthday(string $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
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
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $company
     *
     * @return void
     */
    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param string $consumerscoretype
     *
     * @return void
     */
    public function setConsumerScoreType(string $consumerscoretype): void
    {
        $this->consumerscoretype = $consumerscoretype;
    }

    /**
     * @return string
     */
    public function getConsumerScoreType(): string
    {
        return $this->consumerscoretype;
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
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $firstname
     *
     * @return void
     */
    public function setFirstName(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     *
     * @return void
     */
    public function setLastName(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastname;
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
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $streetname
     *
     * @return void
     */
    public function setStreetName(string $streetname): void
    {
        $this->streetname = $streetname;
    }

    /**
     * @return string
     */
    public function getStreetName(): string
    {
        return $this->streetname;
    }

    /**
     * @param string $streetnumber
     *
     * @return void
     */
    public function setStreetNumber(string $streetnumber): void
    {
        $this->streetnumber = $streetnumber;
    }

    /**
     * @return string
     */
    public function getStreetNumber(): string
    {
        return $this->streetnumber;
    }

    /**
     * @param string $telephonenumber
     *
     * @return void
     */
    public function setTelephoneNumber(string $telephonenumber): void
    {
        $this->telephonenumber = $telephonenumber;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber(): string
    {
        return $this->telephonenumber;
    }

    /**
     * @param string $zip
     *
     * @return void
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return void
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }
}
