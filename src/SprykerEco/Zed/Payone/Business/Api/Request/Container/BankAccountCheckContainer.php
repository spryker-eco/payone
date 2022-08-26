<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class BankAccountCheckContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_BANKACCOUNTCHECK;

    /**
     * @var string|null
     */
    protected $aid;

    /**
     * @var string|null
     */
    protected $checktype;

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
    protected $language;

    /**
     * @var string|null
     */
    protected $iban;

    /**
     * @var string|null
     */
    protected $bic;

    /**
     * @param string|null $bankaccount
     *
     * @return void
     */
    public function setBankAccount(?string $bankaccount = null): void
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
     * @param string|null $bankcode
     *
     * @return void
     */
    public function setBankCode(?string $bankcode = null): void
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
     * @param string|null $bankcountry
     *
     * @return void
     */
    public function setBankCountry(?string $bankcountry = null): void
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
     * @param string $checktype
     *
     * @return void
     */
    public function setCheckType(string $checktype): void
    {
        $this->checktype = $checktype;
    }

    /**
     * @return string|null
     */
    public function getCheckType(): ?string
    {
        return $this->checktype;
    }

    /**
     * @param string|null $iban
     *
     * @return void
     */
    public function setIban(?string $iban = null): void
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
     * @param string|null $bic
     *
     * @return void
     */
    public function setBic(?string $bic = null): void
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
