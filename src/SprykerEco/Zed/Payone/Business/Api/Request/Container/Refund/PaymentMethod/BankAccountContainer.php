<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod;

class BankAccountContainer extends AbstractPaymentMethodContainer
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
     * @var int
     */
    protected $bankcode;

    /**
     * @var int
     */
    protected $bankbranchcode;

    /**
     * @var int
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
    public function setBankaccount(string $bankaccount): void
    {
        $this->bankaccount = $bankaccount;
    }

    /**
     * @return string
     */
    public function getBankaccount(): string
    {
        return $this->bankaccount;
    }

    /**
     * @param int $bankbranchcode
     *
     * @return void
     */
    public function setBankbranchcode(int $bankbranchcode): void
    {
        $this->bankbranchcode = $bankbranchcode;
    }

    /**
     * @return int
     */
    public function getBankbranchcode(): int
    {
        return $this->bankbranchcode;
    }

    /**
     * @param int $bankcheckdigit
     *
     * @return void
     */
    public function setBankcheckdigit(int $bankcheckdigit): void
    {
        $this->bankcheckdigit = $bankcheckdigit;
    }

    /**
     * @return int
     */
    public function getBankcheckdigit(): int
    {
        return $this->bankcheckdigit;
    }

    /**
     * @param int $bankcode
     *
     * @return void
     */
    public function setBankcode(int $bankcode): void
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return int
     */
    public function getBankcode(): int
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankcountry
     *
     * @return void
     */
    public function setBankcountry(string $bankcountry): void
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return string
     */
    public function getBankcountry(): string
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
}
