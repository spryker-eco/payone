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
     * @var string|null
     */
    protected $xid;

    /**
     * @var string|null
     */
    protected $cavv;

    /**
     * @var string|null
     */
    protected $eci;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer|null
     */
    protected $redirect;

    /**
     * @param string $cavv
     *
     * @return void
     */
    public function setCavv(string $cavv): void
    {
        $this->cavv = $cavv;
    }

    /**
     * @return string|null
     */
    public function getCavv(): ?string
    {
        return $this->cavv;
    }

    /**
     * @param string $eci
     *
     * @return void
     */
    public function setEci(string $eci): void
    {
        $this->eci = $eci;
    }

    /**
     * @return string|null
     */
    public function getEci(): ?string
    {
        return $this->eci;
    }

    /**
     * @param string $xid
     *
     * @return void
     */
    public function setXid(string $xid): void
    {
        $this->xid = $xid;
    }

    /**
     * @return string|null
     */
    public function getXid(): ?string
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer|null
     */
    public function getRedirect(): ?RedirectContainer
    {
        return $this->redirect;
    }
}
