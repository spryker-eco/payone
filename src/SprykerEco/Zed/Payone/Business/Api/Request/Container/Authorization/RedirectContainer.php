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
     * @var string|null
     */
    protected $successurl;

    /**
     * @var string|null
     */
    protected $errorurl;

    /**
     * @var string|null
     */
    protected $backurl;

    /**
     * @param string $backurl
     *
     * @return void
     */
    public function setBackUrl(string $backurl): void
    {
        $this->backurl = $backurl;
    }

    /**
     * @return string|null
     */
    public function getBackUrl(): ?string
    {
        return $this->backurl;
    }

    /**
     * @param string $errorurl
     *
     * @return void
     */
    public function setErrorUrl(string $errorurl): void
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @return string|null
     */
    public function getErrorUrl(): ?string
    {
        return $this->errorurl;
    }

    /**
     * @param string $successurl
     *
     * @return void
     */
    public function setSuccessUrl(string $successurl): void
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string|null
     */
    public function getSuccessUrl(): ?string
    {
        return $this->successurl;
    }
}
