<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;

interface PreAuthorizationContainerInterface extends AuthorizationContainerInterface
{
    /**
     * @param string|null $financingType
     *
     * @return void
     */
    public function setFinancingtype(?string $financingType): void;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer $payData
     *
     * @return void
     */
    public function setPaydata(PaydataContainer $payData): void;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer $redirect
     *
     * @return void
     */
    public function setRedirect(RedirectContainer $redirect): void;
}
