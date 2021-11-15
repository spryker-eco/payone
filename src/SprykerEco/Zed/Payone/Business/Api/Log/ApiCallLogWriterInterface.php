<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Log;

use SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface as LogApiCallLogWriterInterface;

interface ApiCallLogWriterInterface
{
    /**
     * @param string $url
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logUrl($url): LogApiCallLogWriterInterface;

    /**
     * @param string $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logRequest($request): LogApiCallLogWriterInterface;

    /**
     * @param string $response
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logResponse($response): LogApiCallLogWriterInterface;

    /**
     * @return $this
     */
    public function flush();
}
