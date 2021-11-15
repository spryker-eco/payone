<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class PrepaymentContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string
     */
    protected $clearing_bankaccountholder;

    /**
     * @var string
     */
    protected $clearing_bankcountry;

    /**
     * @var string
     */
    protected $clearing_bankaccount;

    /**
     * @var string
     */
    protected $clearing_bankcode;

    /**
     * @var string
     */
    protected $clearing_bankiban;

    /**
     * @var string
     */
    protected $clearing_bankbic;

    /**
     * @var string
     */
    protected $clearing_bankcity;

    /**
     * @var string
     */
    protected $clearing_bankname;

    /**
     * @param string $clearingBankAccountHolder
     *
     * @return void
     */
    public function setClearingBankAccountHolder($clearingBankAccountHolder): void
    {
        $this->clearing_bankaccountholder = $clearingBankAccountHolder;
    }

    /**
     * @return string
     */
    public function getClearingBankAccountHolder(): string
    {
        return $this->clearing_bankaccountholder;
    }

    /**
     * @param string $clearingBankCountry
     *
     * @return void
     */
    public function setClearingBankCountry($clearingBankCountry): void
    {
        $this->clearing_bankcountry = $clearingBankCountry;
    }

    /**
     * @return string
     */
    public function getClearingBankCountry(): string
    {
        return $this->clearing_bankcountry;
    }

    /**
     * @param string $clearingBankAccount
     *
     * @return void
     */
    public function setClearingBankAccount($clearingBankAccount): void
    {
        $this->clearing_bankaccount = $clearingBankAccount;
    }

    /**
     * @return string
     */
    public function getClearingBankAccount(): string
    {
        return $this->clearing_bankaccount;
    }

    /**
     * @param string $clearingBankCode
     *
     * @return void
     */
    public function setClearingBankCode($clearingBankCode): void
    {
        $this->clearing_bankcode = $clearingBankCode;
    }

    /**
     * @return string
     */
    public function getClearingBankCode(): string
    {
        return $this->clearing_bankcode;
    }

    /**
     * @param string $clearingBankIban
     *
     * @return void
     */
    public function setClearingBankIban($clearingBankIban): void
    {
        $this->clearing_bankiban = $clearingBankIban;
    }

    /**
     * @return string
     */
    public function getClearingBankIban(): string
    {
        return $this->clearing_bankiban;
    }

    /**
     * @param string $clearingBankBic
     *
     * @return void
     */
    public function setClearingBankBic($clearingBankBic): void
    {
        $this->clearing_bankbic = $clearingBankBic;
    }

    /**
     * @return string
     */
    public function getClearingBankBic(): string
    {
        return $this->clearing_bankbic;
    }

    /**
     * @param string $clearingBankCity
     *
     * @return void
     */
    public function setClearingBankCity($clearingBankCity): void
    {
        $this->clearing_bankcity = $clearingBankCity;
    }

    /**
     * @return string
     */
    public function getClearingBankCity(): string
    {
        return $this->clearing_bankcity;
    }

    /**
     * @param string $clearingBankName
     *
     * @return void
     */
    public function setClearingBankName($clearingBankName): void
    {
        $this->clearing_bankname = $clearingBankName;
    }

    /**
     * @return string
     */
    public function getClearingBankName(): string
    {
        return $this->clearing_bankname;
    }
}
