<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business\Api\Adapter;

use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Adapter\Http\AbstractHttpAdapter;

abstract class AbstractAdapterMock extends AbstractHttpAdapter implements AdapterInterface
{

    /**
     * @const DEFAULT_GETEWAY_URL
     */
    const DEFAULT_GETEWAY_URL = '';

    /**
     * @var bool
     */
    protected $expectSuccess = true;

    /**
     * @param string $paymentGatewayUrl
     */
    public function __construct($paymentGatewayUrl = self::DEFAULT_GETEWAY_URL)
    {
        parent::__construct($paymentGatewayUrl);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function performRequest(array $params)
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
    public function setExpectSuccess($expect = true)
    {
        $this->expectSuccess = $expect;
    }

    /**
     * @return array
     */
    abstract protected function getSuccessResponse();

    /**
     * @return array
     */
    abstract protected function getFailureResponse();

}
