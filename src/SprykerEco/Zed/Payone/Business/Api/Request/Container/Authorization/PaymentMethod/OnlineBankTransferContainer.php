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
     * @var string
     */
    protected $onlinebanktransfertype;

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
    protected $bankgrouptype;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $bic;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
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
     * @return string
     */
    public function getBankAccount(): string
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
     * @param string $bankgrouptype
     *
     * @return void
     */
    public function setBankGroupType(string $bankgrouptype): void
    {
        $this->bankgrouptype = $bankgrouptype;
    }

    /**
     * @return string
     */
    public function getBankGroupType(): string
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
     * @return string
     */
    public function getOnlineBankTransferType(): string
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    public function getRedirect(): RedirectContainer
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
