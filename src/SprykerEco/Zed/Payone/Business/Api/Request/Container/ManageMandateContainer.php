<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class ManageMandateContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_MANAGEMANDATE;

    /**
     * @var string|null
     */
    protected $aid;

    /**
     * @var string|null
     */
    protected $clearingtype;

    /**
     * @var string|null
     */
    protected $mandate_identification;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var int|null
     */
    protected $customerid;

    /**
     * @var string|null
     */
    protected $userid;

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
    protected $email;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string|null
     */
    protected $bankaccount;

    /**
     * @var string|null
     */
    protected $bankcode;

    /**
     * @var string|null
     */
    protected $bankcountry;

    /**
     * @var string|null
     */
    protected $iban;

    /**
     * @var string|null
     */
    protected $bic;

    /**
     * @param string $bankaccount
     *
     * @return void
     */
    public function setBankAccount(string $bankaccount): void
    {
        $this->bankaccount = $bankaccount;
    }

    /**
     * @return string|null
     */
    public function getBankAccount(): ?string
    {
        return $this->bankaccount;
    }

    /**
     * @param string $bankcode
     *
     * @return void
     */
    public function setBankCode(string $bankcode): void
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return string|null
     */
    public function getBankCode(): ?string
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankcountry
     *
     * @return void
     */
    public function setBankCountry(string $bankcountry): void
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return string|null
     */
    public function getBankCountry(): ?string
    {
        return $this->bankcountry;
    }

    /**
     * @param string $clearingtype
     *
     * @return void
     */
    public function setClearingType(string $clearingtype): void
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string|null
     */
    public function getClearingType(): ?string
    {
        return $this->clearingtype;
    }

    /**
     * @param string $mandate_identification
     *
     * @return void
     */
    public function setMandateIdentification(string $mandate_identification): void
    {
        $this->mandate_identification = $mandate_identification;
    }

    /**
     * @return string|null
     */
    public function getMandateIdentification(): ?string
    {
        return $this->mandate_identification;
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
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param int|null $customerid
     *
     * @return void
     */
    public function setCustomerid(?int $customerid): void
    {
        $this->customerid = $customerid;
    }

    /**
     * @return int|null
     */
    public function getCustomerid(): ?int
    {
        return $this->customerid;
    }

    /**
     * @param string $userid
     *
     * @return void
     */
    public function setUserid(string $userid): void
    {
        $this->userid = $userid;
    }

    /**
     * @return string|null
     */
    public function getUserid(): ?string
    {
        return $this->userid;
    }

    /**
     * @param string $firstname
     *
     * @return void
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     *
     * @return void
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
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
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
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
     * @param string $zip
     *
     * @return void
     */
    public function setZip(string $zip): void
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
    public function getCity(): ?string
    {
        return $this->city;
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
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $email
     *
     * @return void
     */
    public function setEmail(?string $email = null): void
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
     * @param string $iban
     *
     * @return void
     */
    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     *
     * @return void
     */
    public function setBic(string $bic): void
    {
        $this->bic = $bic;
    }

    /**
     * @return string|null
     */
    public function getBic(): ?string
    {
        return $this->bic;
    }
}
