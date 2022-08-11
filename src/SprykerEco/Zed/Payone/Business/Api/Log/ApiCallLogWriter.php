<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Log;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLog;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class ApiCallLogWriter implements ApiCallLogWriterInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLog
     */
    protected $logEntity;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     */
    public function __construct(PayoneRepositoryInterface $payoneRepository)
    {
        $this->payoneRepository = $payoneRepository;
        $this->logEntity = new SpyPaymentPayoneApiCallLog();
    }

    /**
     * @param string $url
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logUrl($url): ApiCallLogWriterInterface
    {
        $this->logEntity->setUrl($url);

        return $this;
    }

    /**
     * @param string $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logRequest($request): ApiCallLogWriterInterface
    {
        $this->logEntity->setRequest($request);

        return $this;
    }

    /**
     * @param string $response
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logResponse($response): ApiCallLogWriterInterface
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
