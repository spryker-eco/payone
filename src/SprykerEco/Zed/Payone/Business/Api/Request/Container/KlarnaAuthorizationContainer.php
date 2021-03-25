<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;

class KlarnaAuthorizationContainer extends AbstractAuthorizationContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer|null
     */
    protected $business;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer|null
     */
    protected $paydata;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer|null
     */
    protected $redirect;

    /**
     * @var string|null
     */
    protected $financingtype;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer $business
     *
     * @return $this
     */
    public function setBusiness(BusinessContainer $business): self
    {
        $this->business = $business;

        return $this;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer
     */
    public function getBusiness(): BusinessContainer
    {
        return $this->business;
    }

    /**
     * @return GenericPayment\PaydataContainer
     */
    public function getPaydata(): PaydataContainer
    {
        return $this->paydata;
    }

    /**
     * @param GenericPayment\PaydataContainer $paydata
     *
     * @return void
     */
    public function setPaydata(PaydataContainer $paydata): void
    {
        $this->paydata = $paydata;
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
    public function getRedirect(): RedirectContainer
    {
        return $this->redirect;
    }

    /**
     * @return string
     */
    public function getFinancingtype(): ?string
    {
        return $this->financingtype;
    }

    /**
     * @param string $financingtype
     *
     * @return void
     */
    public function setFinancingtype(string $financingtype): void
    {
        $this->financingtype = $financingtype;
    }
}
