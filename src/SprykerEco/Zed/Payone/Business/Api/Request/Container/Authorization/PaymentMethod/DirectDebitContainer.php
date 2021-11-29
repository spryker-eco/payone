<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class DirectDebitContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string
     */
    protected $bankcountry;

    /**
     * @var string
     */
    protected $bankaccount;

    /**
     * @var string
     */
    protected $bankcode;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $bic;

    /**
     * @var string
     */
    protected $bankaccountholder;

    /**
     * @var string
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
     * @return string
     */
    public function getBankAccount(): string
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
     * @return string
     */
    public function getBankAccountHolder(): string
    {
        return $this->bankaccountholder;
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
     * @return string
     */
    public function getBankCode(): string
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
     * @return string
     */
    public function getBankCountry(): string
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
     * @return string
     */
    public function getIban(): string
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
     * @return string
     */
    public function getBic(): string
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
     * @return string
     */
    public function getMandateIdentification(): string
    {
        return $this->mandate_identification;
    }
}
