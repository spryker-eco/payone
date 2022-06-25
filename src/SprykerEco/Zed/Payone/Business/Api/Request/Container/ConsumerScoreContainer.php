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
     * @var string|null
     */
    protected $aid;

    /**
     * @var string|null
     */
    protected $addresschecktype;

    /**
     * @var string|null
     */
    protected $consumerscoretype;

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
    protected $company;

    /**
     * @var string|null
     */
    protected $street;

    /**
     * @var string|null
     */
    protected $streetname;

    /**
     * @var string|null
     */
    protected $streetnumber;

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
    protected $birthday;

    /**
     * @var string|null
     */
    protected $telephonenumber;

    /**
     * @var string|null
     */
    protected $language;

    /**
     * @var string|null
     */
    protected $gender;

    /**
     * @param string $addressCheckType
     *
     * @return void
     */
    public function setAddressCheckType(string $addressCheckType): void
    {
        $this->addresschecktype = $addressCheckType;
    }

    /**
     * @return string|null
     */
    public function getAddressCheckType(): ?string
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
     * @return string|null
     */
    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    /**
     * @param string|null $city
     *
     * @return void
     */
    public function setCity(?string $city = null): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $company
     *
     * @return void
     */
    public function setCompany(?string $company = null): void
    {
        $this->company = $company;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $consumerscoretype
     *
     * @return void
     */
    public function setConsumerScoreType(?string $consumerscoretype = null): void
    {
        $this->consumerscoretype = $consumerscoretype;
    }

    /**
     * @return string|null
     */
    public function getConsumerScoreType(): ?string
    {
        return $this->consumerscoretype;
    }

    /**
     * @param string|null $country
     *
     * @return void
     */
    public function setCountry(?string $country = null): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $firstname
     *
     * @return void
     */
    public function setFirstName(?string $firstname = null): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $lastname
     *
     * @return void
     */
    public function setLastName(?string $lastname = null): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
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
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $streetname
     *
     * @return void
     */
    public function setStreetName(?string $streetname = null): void
    {
        $this->streetname = $streetname;
    }

    /**
     * @return string|null
     */
    public function getStreetName(): ?string
    {
        return $this->streetname;
    }

    /**
     * @param string|null $streetnumber
     *
     * @return void
     */
    public function setStreetNumber(?string $streetnumber = null): void
    {
        $this->streetnumber = $streetnumber;
    }

    /**
     * @return string|null
     */
    public function getStreetNumber(): ?string
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
     * @return string|null
     */
    public function getTelephoneNumber(): ?string
    {
        return $this->telephonenumber;
    }

    /**
     * @param string|null $zip
     *
     * @return void
     */
    public function setZip(?string $zip = null): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
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
