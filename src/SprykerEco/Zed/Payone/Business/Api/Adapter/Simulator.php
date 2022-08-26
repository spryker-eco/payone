<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Adapter;

use SprykerEco\Zed\Payone\Business\Api\Adapter\Http\AbstractHttpAdapter;

class Simulator extends AbstractHttpAdapter
{
    /**
     * @var string
     */
    protected $rawResponse;

    /**
     * @param array $rawResponse
     *
     * @return void
     */
    public function setRawResponseAsArray(array $rawResponse): void
    {
        $this->rawResponse = $this->createRawResponseFromArray($rawResponse);
    }

    /**
     * @param string $rawResponse
     *
     * @return void
     */
    public function setRawResponseAsString(string $rawResponse): void
    {
        $this->rawResponse = $rawResponse;
    }

    /**
     * @param array $request
     *
     * @return string
     */
    protected function createRawResponseFromArray(array $request): string
    {
        $rawResponse = '';
        $arrayCount = count($request);
        $count = 1;
        foreach ($request as $key => $value) {
            $rawResponse .= $key . '=' . $value;
            if ($count < $arrayCount) {
                $rawResponse .= "\n";
            }
            $count++;
        }

        return $rawResponse;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function performRequest(array $params): array
    {
        $this->setRawResponse($this->rawResponse);
        $response = explode("\n", $this->rawResponse);

        return $response;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return '';
    }
}
