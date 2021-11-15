<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class ThreeDSecureContainer extends AbstractContainer
{
    /**
     * @var string
     */
    protected $xid;

    /**
     * @var string
     */
    protected $cavv;

    /**
     * @var string
     */
    protected $eci;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $cavv
     *
     * @return void
     */
    public function setCavv($cavv): void
    {
        $this->cavv = $cavv;
    }

    /**
     * @return string
     */
    public function getCavv(): string
    {
        return $this->cavv;
    }

    /**
     * @param string $eci
     *
     * @return void
     */
    public function setEci($eci): void
    {
        $this->eci = $eci;
    }

    /**
     * @return string
     */
    public function getEci(): string
    {
        return $this->eci;
    }

    /**
     * @param string $xid
     *
     * @return void
     */
    public function setXid($xid): void
    {
        $this->xid = $xid;
    }

    /**
     * @return string
     */
    public function getXid(): string
    {
        return $this->xid;
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
