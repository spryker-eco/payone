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

class AbstractPayoneKlarnaAuthorizationContainer extends AbstractAuthorizationContainer
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
     * @return void
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer|null
     */
    public function getBusiness(): ?ContainerInterface
    {
        return $this->business;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer|null
     */
    public function getPaydata(): ?ContainerInterface
    {
        return $this->paydata;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer $payData
     *
     * @return void
     */
    public function setPaydata(PaydataContainer $payData): void
    {
        $this->paydata = $payData;
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
    public function getRedirect(): ?ContainerInterface
    {
        return $this->redirect;
    }

    /**
     * @return string|null
     */
    public function getFinancingtype(): ?string
    {
        return $this->financingtype;
    }

    /**
     * @param string $financingType
     *
     * @return void
     */
    public function setFinancingtype(string $financingType): void
    {
        $this->financingtype = $financingType;
    }
}
