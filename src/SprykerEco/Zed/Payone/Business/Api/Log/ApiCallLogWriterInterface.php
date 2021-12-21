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
    public function logUrl($url);

    /**
     * @param string $request
     *
     * @return $this
     */
    public function logRequest($request);

    /**
     * @param string $response
     *
     * @return $this
     */
    public function logResponse($response);

    /**
     * @return $this
     */
    public function flush();
}
