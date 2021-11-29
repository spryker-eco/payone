<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Log;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLog;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class ApiCallLogWriter implements ApiCallLogWriterInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $payoneQueryContainer;

    /**
     * @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLog
     */
    protected $logEntity;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $payoneQueryContainer
     */
    public function __construct(PayoneQueryContainerInterface $payoneQueryContainer)
    {
        $this->payoneQueryContainer = $payoneQueryContainer;
        $this->logEntity = new SpyPaymentPayoneApiCallLog();
    }

    /**
     * @param string $url
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logUrl(string $url): ApiCallLogWriterInterface
    {
        $this->logEntity->setUrl($url);

        return $this;
    }

    /**
     * @param string $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logRequest(string $request): ApiCallLogWriterInterface
    {
        $this->logEntity->setRequest($request);

        return $this;
    }

    /**
     * @param string $response
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logResponse(string $response): ApiCallLogWriterInterface
    {
        $this->logEntity->setResponse($response);

        return $this;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function flush(): ApiCallLogWriterInterface
    {
        $this->logEntity->save();

        return $this;
    }
}
