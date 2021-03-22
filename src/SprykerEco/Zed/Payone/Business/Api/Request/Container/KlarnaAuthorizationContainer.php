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
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer
     */
    protected $business;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer
     */
    protected $paydata;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected $redirect;

    /**
     * @var string
     */
    protected $financingtype;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer $business
     *
     * @return $this
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer
     */
    public function getBusiness()
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
    public function setRedirect(RedirectContainer $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @return string
     */
    public function getFinancingtype(): string
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
