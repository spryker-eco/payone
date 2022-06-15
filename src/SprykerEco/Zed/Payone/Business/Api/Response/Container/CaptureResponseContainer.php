<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class CaptureResponseContainer extends AbstractResponseContainer
{
    /**
     * @var int|null
     */
    protected $txid;

    /**
     * @var string|null
     */
    protected $settleaccount;

    /**
     * @var string|null
     */
    protected $clearing_bankaccountholder;

    /**
     * @var string|null
     */
    protected $clearing_bankcountry;

    /**
     * @var string|null
     */
    protected $clearing_bankaccount;

    /**
     * @var string|null
     */
    protected $clearing_bankcode;

    /**
     * @var string|null
     */
    protected $clearing_bankiban;

    /**
     * @var string|null
     */
    protected $clearing_bankbic;

    /**
     * @var string|null
     */
    protected $clearing_bankcity;

    /**
     * @var string|null
     */
    protected $clearing_bankname;

    /**
     * @var string|null
     */
    protected $clearing_legalnote;

    /**
     * (YYYYMMDD)
     *
     * @var string|null
     */
    protected $clearing_duedate;

    /**
     * @var string|null
     */
    protected $clearing_reference;

    /**
     * @var string|null
     */
    protected $clearing_instructionnote;

    /**
     * @var string|null
     */
    protected $mandate_identification;

    /**
     * @var string|null
     */
    protected $creditor_identifier;

    /**
     * @var string|null
     */
    protected $creditor_name;

    /**
     * @var string|null
     */
    protected $creditor_street;

    /**
     * @var string|null
     */
    protected $creditor_zip;

    /**
     * @var string|null
     */
    protected $creditor_city;

    /**
     * @var string|null
     */
    protected $creditor_country;

    /**
     * @var string|null
     */
    protected $creditor_email;

    /**
     * @var string|null
     */
    protected $clearing_date;

    /**
     * @var string|null
     */
    protected $clearing_amount;

    /**
     * @param string $clearing_bankaccount
     *
     * @return void
     */
    public function setClearingBankaccount(string $clearing_bankaccount): void
    {
        $this->clearing_bankaccount = $clearing_bankaccount;
    }

    /**
     * @return string|null
     */
    public function getClearingBankaccount(): ?string
    {
        return $this->clearing_bankaccount;
    }

    /**
     * @param string $clearing_bankaccountholder
     *
     * @return void
     */
    public function setClearingBankaccountholder(string $clearing_bankaccountholder): void
    {
        $this->clearing_bankaccountholder = $clearing_bankaccountholder;
    }

    /**
     * @return string|null
     */
    public function getClearingBankaccountholder(): ?string
    {
        return $this->clearing_bankaccountholder;
    }

    /**
     * @param string $clearing_bankbic
     *
     * @return void
     */
    public function setClearingBankbic(string $clearing_bankbic): void
    {
        $this->clearing_bankbic = $clearing_bankbic;
    }

    /**
     * @return string|null
     */
    public function getClearingBankbic(): ?string
    {
        return $this->clearing_bankbic;
    }

    /**
     * @param string $clearing_bankcity
     *
     * @return void
     */
    public function setClearingBankcity(string $clearing_bankcity): void
    {
        $this->clearing_bankcity = $clearing_bankcity;
    }

    /**
     * @return string|null
     */
    public function getClearingBankcity(): ?string
    {
        return $this->clearing_bankcity;
    }

    /**
     * @param string $clearing_bankcode
     *
     * @return void
     */
    public function setClearingBankcode(string $clearing_bankcode): void
    {
        $this->clearing_bankcode = $clearing_bankcode;
    }

    /**
     * @return string|null
     */
    public function getClearingBankcode(): ?string
    {
        return $this->clearing_bankcode;
    }

    /**
     * @param string $clearing_bankcountry
     *
     * @return void
     */
    public function setClearingBankcountry(string $clearing_bankcountry): void
    {
        $this->clearing_bankcountry = $clearing_bankcountry;
    }

    /**
     * @return string|null
     */
    public function getClearingBankcountry(): ?string
    {
        return $this->clearing_bankcountry;
    }

    /**
     * @param string $clearing_bankiban
     *
     * @return void
     */
    public function setClearingBankiban(string $clearing_bankiban): void
    {
        $this->clearing_bankiban = $clearing_bankiban;
    }

    /**
     * @return string|null
     */
    public function getClearingBankiban(): ?string
    {
        return $this->clearing_bankiban;
    }

    /**
     * @param string $clearing_bankname
     *
     * @return void
     */
    public function setClearingBankname(string $clearing_bankname): void
    {
        $this->clearing_bankname = $clearing_bankname;
    }

    /**
     * @return string|null
     */
    public function getClearingBankname(): ?string
    {
        return $this->clearing_bankname;
    }

    /**
     * @param string $settleaccount
     *
     * @return void
     */
    public function setSettleaccount(string $settleaccount): void
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string|null
     */
    public function getSettleaccount(): ?string
    {
        return $this->settleaccount;
    }

    /**
     * @param int $txid
     *
     * @return void
     */
    public function setTxid(int $txid): void
    {
        $this->txid = $txid;
    }

    /**
     * @return int|null
     */
    public function getTxid(): ?int
    {
        return $this->txid;
    }

    /**
     * @param string $clearing_duedate
     *
     * @return void
     */
    public function setClearingDuedate(string $clearing_duedate): void
    {
        $this->clearing_duedate = $clearing_duedate;
    }

    /**
     * @return string|null
     */
    public function getClearingDuedate(): ?string
    {
        return $this->clearing_duedate;
    }

    /**
     * @param string $clearing_instructionnote
     *
     * @return void
     */
    public function setClearingInstructionnote(string $clearing_instructionnote): void
    {
        $this->clearing_instructionnote = $clearing_instructionnote;
    }

    /**
     * @return string|null
     */
    public function getClearingInstructionnote(): ?string
    {
        return $this->clearing_instructionnote;
    }

    /**
     * @param string $clearing_legalnote
     *
     * @return void
     */
    public function setClearingLegalnote(string $clearing_legalnote): void
    {
        $this->clearing_legalnote = $clearing_legalnote;
    }

    /**
     * @return string|null
     */
    public function getClearingLegalnote(): ?string
    {
        return $this->clearing_legalnote;
    }

    /**
     * @param string $clearing_reference
     *
     * @return void
     */
    public function setClearingReference(string $clearing_reference): void
    {
        $this->clearing_reference = $clearing_reference;
    }

    /**
     * @return string|null
     */
    public function getClearingReference(): ?string
    {
        return $this->clearing_reference;
    }

    /**
     * @param string $creditorCity
     *
     * @return void
     */
    public function setCreditorCity(string $creditorCity): void
    {
        $this->creditor_city = $creditorCity;
    }

    /**
     * @return string|null
     */
    public function getCreditorCity(): ?string
    {
        return $this->creditor_city;
    }

    /**
     * @param string $creditorCountry
     *
     * @return void
     */
    public function setCreditorCountry(string $creditorCountry): void
    {
        $this->creditor_country = $creditorCountry;
    }

    /**
     * @return string|null
     */
    public function getCreditorCountry(): ?string
    {
        return $this->creditor_country;
    }

    /**
     * @param string $creditorEmail
     *
     * @return void
     */
    public function setCreditorEmail(string $creditorEmail): void
    {
        $this->creditor_email = $creditorEmail;
    }

    /**
     * @return string|null
     */
    public function getCreditorEmail(): ?string
    {
        return $this->creditor_email;
    }

    /**
     * @param string $creditorIdentifier
     *
     * @return void
     */
    public function setCreditorIdentifier(string $creditorIdentifier): void
    {
        $this->creditor_identifier = $creditorIdentifier;
    }

    /**
     * @return string|null
     */
    public function getCreditorIdentifier(): ?string
    {
        return $this->creditor_identifier;
    }

    /**
     * @param string $creditorName
     *
     * @return void
     */
    public function setCreditorName(string $creditorName): void
    {
        $this->creditor_name = $creditorName;
    }

    /**
     * @return string|null
     */
    public function getCreditorName(): ?string
    {
        return $this->creditor_name;
    }

    /**
     * @param string $creditorStreet
     *
     * @return void
     */
    public function setCreditorStreet(string $creditorStreet): void
    {
        $this->creditor_street = $creditorStreet;
    }

    /**
     * @return string|null
     */
    public function getCreditorStreet(): ?string
    {
        return $this->creditor_street;
    }

    /**
     * @param string $creditorZip
     *
     * @return void
     */
    public function setCreditorZip(string $creditorZip): void
    {
        $this->creditor_zip = $creditorZip;
    }

    /**
     * @return string|null
     */
    public function getCreditorZip(): ?string
    {
        return $this->creditor_zip;
    }

    /**
     * @param string $mandateIdentification
     *
     * @return void
     */
    public function setMandateIdentification(string $mandateIdentification): void
    {
        $this->mandate_identification = $mandateIdentification;
    }

    /**
     * @return string|null
     */
    public function getMandateIdentification(): ?string
    {
        return $this->mandate_identification;
    }

    /**
     * @param string $clearingAmount
     *
     * @return void
     */
    public function setClearingAmount(string $clearingAmount): void
    {
        $this->clearing_amount = $clearingAmount;
    }

    /**
     * @return string|null
     */
    public function getClearingAmount(): ?string
    {
        return $this->clearing_amount;
    }

    /**
     * @param string $clearingDate
     *
     * @return void
     */
    public function setClearingDate(string $clearingDate): void
    {
        $this->clearing_date = $clearingDate;
    }

    /**
     * @return string|null
     */
    public function getClearingDate(): ?string
    {
        return $this->clearing_date;
    }
}
