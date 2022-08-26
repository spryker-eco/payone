<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Adapter\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface;
use SprykerEco\Zed\Payone\Business\Exception\TimeoutException;

class Guzzle extends AbstractHttpAdapter
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface
     */
    protected $logger;

    /**
     * @param string $paymentGatewayUrl
     * @param \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriterInterface $logger
     */
    public function __construct(string $paymentGatewayUrl, ApiCallLogWriterInterface $logger)
    {
        parent::__construct($paymentGatewayUrl);

        $this->logger = $logger;
        $this->client = new Client([
            'timeout' => $this->getTimeout(),
        ]);
    }

    /**
     * @param array $params
     *
     * @throws \SprykerEco\Zed\Payone\Business\Exception\TimeoutException
     *
     * @return array
     */
    protected function performRequest(array $params): array
    {
        $urlArray = $this->generateUrlArray($params);

        $urlHost = $urlArray['host'];
        $urlPath = $urlArray['path'] ?? '';
        $urlScheme = $urlArray['scheme'];

        $url = $urlScheme . '://' . $urlHost . $urlPath;

        $this->logger
            ->logUrl($url)
            ->logRequest(print_r($params, true));
        try {
            $response = $this->client->post($url, ['form_params' => $params]);
        } catch (ConnectException $e) {
            $this->logger->flush();

            throw new TimeoutException('Timeout - Payone Communication: ' . $e->getMessage());
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        $result = (string)$response->getBody();
        $this->logger
            ->logResponse($result)
            ->flush();
        $result = explode("\n", $result);

        return $result;
    }
}
