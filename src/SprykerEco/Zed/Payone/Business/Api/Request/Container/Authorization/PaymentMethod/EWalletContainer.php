<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class EWalletContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string|null
     */
    protected $wallettype;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer|null
     */
    protected $redirect;

    /**
     * @param string $wallettype
     *
     * @return void
     */
    public function setWalletType(string $wallettype): void
    {
        $this->wallettype = $wallettype;
    }

    /**
     * @return string|null
     */
    public function getWalletType(): ?string
    {
        return $this->wallettype;
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
}
