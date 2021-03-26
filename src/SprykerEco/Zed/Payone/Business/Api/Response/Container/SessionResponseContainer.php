<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class SessionResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string|null
     */
    protected $response;

    /**
     * @param string $response
     *
     * @return void
     */
    public function setResponse(string $response): void
    {
        $this->response = $response;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }
}
