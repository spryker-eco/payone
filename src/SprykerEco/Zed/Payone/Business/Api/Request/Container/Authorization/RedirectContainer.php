<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class RedirectContainer extends AbstractContainer
{
    /**
     * @var string
     */
    protected $successurl;

    /**
     * @var string
     */
    protected $errorurl;

    /**
     * @var string
     */
    protected $backurl;

    /**
     * @param string $backurl
     *
     * @return void
     */
    public function setBackUrl($backurl): void
    {
        $this->backurl = $backurl;
    }

    /**
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->backurl;
    }

    /**
     * @param string $errorurl
     *
     * @return void
     */
    public function setErrorUrl($errorurl): void
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @return string
     */
    public function getErrorUrl(): string
    {
        return $this->errorurl;
    }

    /**
     * @param string $successurl
     *
     * @return void
     */
    public function setSuccessUrl($successurl): void
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successurl;
    }
}
