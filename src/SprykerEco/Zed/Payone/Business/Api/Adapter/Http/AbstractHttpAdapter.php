<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Adapter\Http;

use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Exception\TimeoutException;

abstract class AbstractHttpAdapter implements AdapterInterface
{
    /**
     * @var int
     */
    public const DEFAULT_TIMEOUT = 45;

    /**
     * @var int
     */
    protected $timeout = self::DEFAULT_TIMEOUT;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $rawResponse;

    /**
     * @param string $paymentGatewayUrl
     */
    public function __construct(string $paymentGatewayUrl)
    {
        $this->url = $paymentGatewayUrl;
    }

    /**
     * @param int $timeout
     *
     * @return void
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function sendRawRequest(array $params): array
    {
        $rawResponse = $this->performRequest($params);
        $result = $this->parseResponse($rawResponse);

        return $result;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     * @param array $additionalParams
     *
     * @return array
     */
    public function sendRequest(AbstractRequestContainer $container, array $additionalParams = []): array
    {
        try {
            $requestParams = array_merge($container->toArray(), $additionalParams);
            ksort($requestParams);

            return $this->sendRawRequest($requestParams);
        } catch (TimeoutException $exception) {
            return [
                'status' => 'TIMEOUT',
            ];
        }
    }

    /**
     * @param array $params
     *
     * @return array
     */
    abstract protected function performRequest(array $params): array;

    /**
     * @param array $params
     *
     * @return array
     */
    protected function generateUrlArray(array $params): array
    {
        $urlRequest = $this->getUrl() . '?' . http_build_query($params, '', '&');
        $urlArray = parse_url($urlRequest);

        return $urlArray;
    }

    /**
     * @param array $responseRaw
     *
     * @return array
     */
    protected function parseResponse(array $responseRaw = []): array
    {
        $result = [];

        if (count($responseRaw) === 0) {
            return $result;
        } elseif (strpos($responseRaw[0], '%PDF-') !== false) {
            return ['rawResponse' => base64_encode(implode("\n", array_values($responseRaw)))];
        }

        foreach ($responseRaw as $key => $line) {
            $pos = strpos($line, '=');

            if ($pos === false) {
                if (strlen($line) > 0) {
                    $result[$key] = $line;
                }

                continue;
            }

            $lineArray = explode('=', $line);
            $resultKey = array_shift($lineArray);
            $result[$resultKey] = implode('=', $lineArray);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    /**
     * @param string $rawResponse
     *
     * @return void
     */
    protected function setRawResponse(string $rawResponse): void
    {
        $this->rawResponse = $rawResponse;
    }
}
