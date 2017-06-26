<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Adapter\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use SprykerEco\Zed\Payone\Business\Exception\TimeoutException;

class Guzzle extends AbstractHttpAdapter
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @param string $paymentGatewayUrl
     */
    public function __construct($paymentGatewayUrl)
    {
        parent::__construct($paymentGatewayUrl);

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
    protected function performRequest(array $params)
    {
        $urlArray = $this->generateUrlArray($params);

        $urlHost = $urlArray['host'];
        $urlPath = isset($urlArray['path']) ? $urlArray['path'] : '';
        $urlScheme = $urlArray['scheme'];

        $url = $urlScheme . '://' . $urlHost . $urlPath;

        try {
            $response = $this->client->post($url, ['form_params' => $params]);
        } catch (ConnectException $e) {
            throw new TimeoutException('Timeout - Payone Communication: ' . $e->getMessage());
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        $result = (string)$response->getBody();
        $result = explode("\n", $result);

        return $result;
    }

}
