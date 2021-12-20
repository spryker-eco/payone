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
     * @return $this
     */
    public function logUrl(string $url): LogApiCallLogWriterInterface;

    /**
     * @param string $request
     *
     * @return $this
     */
    public function logRequest(string $request): LogApiCallLogWriterInterface;

    /**
     * @param string $response
     *
     * @return $this
     */
    public function logResponse(string $response): LogApiCallLogWriterInterface;

    /**
     * @return $this
     */
    public function flush();
}
