<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class BankAccountCheckResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected $bankcountry;

    /**
     * @var string
     */
    protected $bankcode;

    /**
     * @var string
     */
    protected $bankaccount;

    /**
     * @var string
     */
    protected $bankbranchcode;

    /**
     * @var string
     */
    protected $bankcheckdigit;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
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
     * @return string
     */
    public function getBankAccount(): string
    {
        return $this->bankaccount;
    }

    /**
     * @param string $bankbranchcode
     *
     * @return void
     */
    public function setBankBranchCode(string $bankbranchcode): void
    {
        $this->bankbranchcode = $bankbranchcode;
    }

    /**
     * @return string
     */
    public function getBankBranchCode(): string
    {
        return $this->bankbranchcode;
    }

    /**
     * @param string $bankcheckdigit
     *
     * @return void
     */
    public function setBankCheckDigit(string $bankcheckdigit): void
    {
        $this->bankcheckdigit = $bankcheckdigit;
    }

    /**
     * @return string
     */
    public function getBankCheckDigit(): string
    {
        return $this->bankcheckdigit;
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
}
