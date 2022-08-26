<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class PrepaymentContainer extends AbstractPaymentMethodContainer
{
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
     * @param string $clearingBankAccountHolder
     *
     * @return void
     */
    public function setClearingBankAccountHolder(string $clearingBankAccountHolder): void
    {
        $this->clearing_bankaccountholder = $clearingBankAccountHolder;
    }

    /**
     * @return string|null
     */
    public function getClearingBankAccountHolder(): ?string
    {
        return $this->clearing_bankaccountholder;
    }

    /**
     * @param string $clearingBankCountry
     *
     * @return void
     */
    public function setClearingBankCountry(string $clearingBankCountry): void
    {
        $this->clearing_bankcountry = $clearingBankCountry;
    }

    /**
     * @return string|null
     */
    public function getClearingBankCountry(): ?string
    {
        return $this->clearing_bankcountry;
    }

    /**
     * @param string $clearingBankAccount
     *
     * @return void
     */
    public function setClearingBankAccount(string $clearingBankAccount): void
    {
        $this->clearing_bankaccount = $clearingBankAccount;
    }

    /**
     * @return string|null
     */
    public function getClearingBankAccount(): ?string
    {
        return $this->clearing_bankaccount;
    }

    /**
     * @param string $clearingBankCode
     *
     * @return void
     */
    public function setClearingBankCode(string $clearingBankCode): void
    {
        $this->clearing_bankcode = $clearingBankCode;
    }

    /**
     * @return string|null
     */
    public function getClearingBankCode(): ?string
    {
        return $this->clearing_bankcode;
    }

    /**
     * @param string $clearingBankIban
     *
     * @return void
     */
    public function setClearingBankIban(string $clearingBankIban): void
    {
        $this->clearing_bankiban = $clearingBankIban;
    }

    /**
     * @return string|null
     */
    public function getClearingBankIban(): ?string
    {
        return $this->clearing_bankiban;
    }

    /**
     * @param string $clearingBankBic
     *
     * @return void
     */
    public function setClearingBankBic(string $clearingBankBic): void
    {
        $this->clearing_bankbic = $clearingBankBic;
    }

    /**
     * @return string|null
     */
    public function getClearingBankBic(): ?string
    {
        return $this->clearing_bankbic;
    }

    /**
     * @param string $clearingBankCity
     *
     * @return void
     */
    public function setClearingBankCity(string $clearingBankCity): void
    {
        $this->clearing_bankcity = $clearingBankCity;
    }

    /**
     * @return string|null
     */
    public function getClearingBankCity(): ?string
    {
        return $this->clearing_bankcity;
    }

    /**
     * @param string $clearingBankName
     *
     * @return void
     */
    public function setClearingBankName(string $clearingBankName): void
    {
        $this->clearing_bankname = $clearingBankName;
    }

    /**
     * @return string|null
     */
    public function getClearingBankName(): ?string
    {
        return $this->clearing_bankname;
    }
}
