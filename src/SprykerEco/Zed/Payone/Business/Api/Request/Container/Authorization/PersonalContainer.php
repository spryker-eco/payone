<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class PersonalContainer extends AbstractContainer
{
    /**
     * Merchant's customer ID (Permitted symbols: 0-9, a-z, A-Z, .,-,_,/)
     *
     * @var string
     */
    protected $customerid;

    /**
     * PAYONE debtor ID
     *
     * @var string
     */
    protected $userid;

    /**
     * @var string|null
     */
    protected $salutation;

    /**
     * @var string
     */
    protected $title;

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
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $addressaddition;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string|null
     */
    protected $city;

    /**
     * Country (ISO-3166)
     *
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $telephonenumber;

    /**
     * Date of birth (YYYYMMDD)
     *
     * @var string
     */
    protected $birthday;

    /**
     * Language indicator (ISO639)
     *
     * @var string|null
     */
    protected $language;

    /**
     * @var string
     */
    protected $vatid;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var string
     */
    protected $personalId;

    /**
     * @var string|null
     */
    protected $ip;

    /**
     * @param string $addressaddition
     *
     * @return void
     */
    public function setAddressAddition(string $addressaddition): void
    {
        $this->addressaddition = $addressaddition;
    }

    /**
     * @return string|null
     */
    public function getAddressAddition(): ?string
    {
        return $this->addressaddition;
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
     * @param string|null $city
     *
     * @return void
     */
    public function setCity(?string $city): void
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
    public function setCompany($company): void
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
     * @param string $customerid
     *
     * @return void
     */
    public function setCustomerId(string $customerid): void
    {
        $this->customerid = $customerid;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerid;
    }

    /**
     * @param string|null $email
     *
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $firstname
     *
     * @return void
     */
    public function setFirstName(?string $firstname): void
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
     * @param string|null $ip
     *
     * @return void
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $language
     *
     * @return void
     */
    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string|null $lastname
     *
     * @return void
     */
    public function setLastName(?string $lastname): void
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
     * @param string|null $salutation
     *
     * @return void
     */
    public function setSalutation(?string $salutation): void
    {
        $this->salutation = $salutation;
    }

    /**
     * @return string|null
     */
    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    /**
     * @param string $state
     *
     * @return void
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
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
     * @param string|null $telephonenumber
     *
     * @return void
     */
    public function setTelephoneNumber(?string $telephonenumber): void
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
     * @param string $title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $userid
     *
     * @return void
     */
    public function setUserId(string $userid): void
    {
        $this->userid = $userid;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userid;
    }

    /**
     * @param string $vatid
     *
     * @return void
     */
    public function setVatId(string $vatid): void
    {
        $this->vatid = $vatid;
    }

    /**
     * @return string
     */
    public function getVatId(): string
    {
        return $this->vatid;
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

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $personalId
     *
     * @return void
     */
    public function setPersonalId(string $personalId): void
    {
        $this->personalId = $personalId;
    }

    /**
     * @return string
     */
    public function getPersonalId(): string
    {
        return $this->personalId;
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
}
