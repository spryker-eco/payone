<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class FinancingContainer extends AbstractPaymentMethodContainer
{
    /**
     * Enum FinancingType
     *
     * @var string
     */
    protected $financingtype;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $financingtype
     *
     * @return void
     */
    public function setFinancingType(string $financingtype): void
    {
        $this->financingtype = $financingtype;
    }

    /**
     * @return string
     */
    public function getFinancingType(): string
    {
        return $this->financingtype;
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
}
