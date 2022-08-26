<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod;

class BankAccountContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string|null
     */
    protected $bankcountry;

    /**
     * @var string|null
     */
    protected $bankaccount;

    /**
     * @var int|null
     */
    protected $bankcode;

    /**
     * @var int|null
     */
    protected $bankbranchcode;

    /**
     * @var int|null
     */
    protected $bankcheckdigit;

    /**
     * @var string|null
     */
    protected $bankaccountholder;

    /**
     * @var string|null
     */
    protected $iban;

    /**
     * @var string|null
     */
    protected $bic;

    /**
     * @var string|null
     */
    protected $mandate_identification;

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
     * @param string $bankaccountholder
     *
     * @return void
     */
    public function setBankAccountHolder(string $bankaccountholder): void
    {
        $this->bankaccountholder = $bankaccountholder;
    }

    /**
     * @return string|null
     */
    public function getBankAccountHolder(): ?string
    {
        return $this->bankaccountholder;
    }

    /**
     * @param int $bankbranchcode
     *
     * @return void
     */
    public function setBankBranchCode(int $bankbranchcode): void
    {
        $this->bankbranchcode = $bankbranchcode;
    }

    /**
     * @return int|null
     */
    public function getBankBranchCode(): ?int
    {
        return $this->bankbranchcode;
    }

    /**
     * @param int $bankcheckdigit
     *
     * @return void
     */
    public function setBankCheckDigit(int $bankcheckdigit): void
    {
        $this->bankcheckdigit = $bankcheckdigit;
    }

    /**
     * @return int|null
     */
    public function getBankCheckDigit(): ?int
    {
        return $this->bankcheckdigit;
    }

    /**
     * @param int $bankcode
     *
     * @return void
     */
    public function setBankCode(int $bankcode): void
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return int|null
     */
    public function getBankCode(): ?int
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
}
