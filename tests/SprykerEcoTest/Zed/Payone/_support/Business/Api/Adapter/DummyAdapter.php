<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

use SprykerEco\Zed\Payone\Business\Api\Adapter\Http\AbstractHttpAdapter;

class DummyAdapter extends AbstractHttpAdapter
{
    /**
     * @param string $response
     */
    public function __construct(string $response)
    {
        parent::__construct('');

        $this->rawResponse = $response;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function performRequest(array $params): array
    {
        return $params;
    }

    /**
     * @param array $responseRaw
     *
     * @return array
     */
    protected function parseResponse(array $responseRaw = []): array
    {
        return json_decode($this->rawResponse, true);
    }
}
