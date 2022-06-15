<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class GetInvoiceResponseContainer extends AbstractResponseContainer
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->isError()) {
            $result = parent::__toString();
        } else {
            $stringArray = ['status=' . $this->getStatus(), 'data=PDF-Content'];
            $result = implode('|', $stringArray);
        }

        return $result;
    }
}
