<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Adapter\Http\AbstractHttpAdapter;

abstract class AbstractAdapterMock extends AbstractHttpAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    public const DEFAULT_GETEWAY_URL = '';

    /**
     * @var bool
     */
    protected $expectSuccess = true;

    /**
     * @param string $paymentGatewayUrl
     */
    public function __construct(string $paymentGatewayUrl = self::DEFAULT_GETEWAY_URL)
    {
        parent::__construct($paymentGatewayUrl);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function performRequest(array $params): array
    {
        if ($this->expectSuccess) {
            return $this->getSuccessResponse();
        }

        return $this->getFailureResponse();
    }

    /**
     * @param bool $expect
     *
     * @return void
     */
    public function setExpectSuccess(bool $expect = true): void
    {
        $this->expectSuccess = $expect;
    }

    /**
     * @return array
     */
    abstract protected function getSuccessResponse(): array;

    /**
     * @return array
     */
    abstract protected function getFailureResponse(): array;
}
