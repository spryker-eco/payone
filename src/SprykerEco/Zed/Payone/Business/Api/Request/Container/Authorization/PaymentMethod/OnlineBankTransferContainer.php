<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class OnlineBankTransferContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string|null
     */
    protected $onlinebanktransfertype;

    /**
     * @var string|null
     */
    protected $bankcountry;

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
    protected $bankgrouptype;

    /**
     * @var string|null
     */
    protected $iban;

    /**
     * @var string|null
     */
    protected $bic;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer|null
     */
    protected $redirect;

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
     * @param string $bankgrouptype
     *
     * @return void
     */
    public function setBankGroupType(string $bankgrouptype): void
    {
        $this->bankgrouptype = $bankgrouptype;
    }

    /**
     * @return string|null
     */
    public function getBankGroupType(): ?string
    {
        return $this->bankgrouptype;
    }

    /**
     * @param string $onlinebanktransfertype
     *
     * @return void
     */
    public function setOnlineBankTransferType(string $onlinebanktransfertype): void
    {
        $this->onlinebanktransfertype = $onlinebanktransfertype;
    }

    /**
     * @return string|null
     */
    public function getOnlineBankTransferType(): ?string
    {
        return $this->onlinebanktransfertype;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer $redirect
     *
     * @return void
     */
    public function setRedirect(RedirectContainer $redirect): void
    {
        $this->redirect = $redirect;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer|null
     */
    public function getRedirect(): ?RedirectContainer
    {
        return $this->redirect;
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
