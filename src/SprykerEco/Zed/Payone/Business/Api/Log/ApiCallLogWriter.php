<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Log;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLog;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class ApiCallLogWriter implements ApiCallLogWriterInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $payoneQueryContainer;

    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected $payoneConfig;

    /**
     * @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLog
     */
    protected $logEntity;

    public function __construct(
        PayoneQueryContainerInterface $payoneQueryContainer,
        PayoneConfig $payoneConfig)
    {
        $this->payoneQueryContainer = $payoneQueryContainer;
        $this->payoneConfig = $payoneConfig;
        $this->logEntity = new SpyPaymentPayoneApiCallLog();
    }

    /**
     * @param string $url
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logUrl($url)
    {
        $this->logEntity->setUrl($url);

        return $this;
    }

    /**
     * @param string $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logRequest($request)
    {
        $this->logEntity->setRequest($request);

        return $this;
    }

    /**
     * @param string $response
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logResponse($response)
    {
        $this->logEntity->setResponse($response);

        return $this;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function flush()
    {
        if (!$this->isCallLoggingEnabled()) {
            return $this;
        }
        
        $this->logEntity->save();

        return $this;
    }

    /**
     * @return bool
     */
    protected function isCallLoggingEnabled()
    {
        return $this->payoneConfig->isCallLoggingEnabled();
    }
}