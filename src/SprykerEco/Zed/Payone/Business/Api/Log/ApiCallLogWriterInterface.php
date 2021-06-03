<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Log;

interface ApiCallLogWriterInterface
{
    /**
     * @param string $url
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logUrl($url);

    /**
     * @param string $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logRequest($request);

    /**
     * @param string $response
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    public function logResponse($response);

    /**
     * @return $this
     */
    public function flush();
}
