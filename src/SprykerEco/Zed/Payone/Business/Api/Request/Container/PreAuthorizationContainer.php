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

class PreAuthorizationContainer extends AbstractAuthorizationContainer implements PreAuthorizationContainerInterface
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION;

    /**
     * @var string
     */
    protected $workorderid;

    /**
     * @var string
     */
    protected $financingtype;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer|null
     */
    protected $paydata;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer|null
     */
    protected $redirect;

    /**
     * @param string $workorderid
     *
     * @return void
     */
    public function setWorkOrderId($workorderid): void
    {
        $this->workorderid = $workorderid;
    }

    /**
     * @return string
     */
    public function getWorkOrderId(): ?string
    {
        return $this->workorderid;
    }

    /**
     * @return string
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
}
