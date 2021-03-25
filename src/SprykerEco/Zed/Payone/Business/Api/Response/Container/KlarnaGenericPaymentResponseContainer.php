<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class KlarnaGenericPaymentResponseContainer extends AbstractResponseContainer
{
    const ADD_PAYDATA_REPLACEMENT_PATTERN = '/add_paydata\[(.*)\]/';
    /**
     * @var string
     */
    protected $workorderid;

    /**
     * @var string
     */
    protected $client_token;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * @return string
     */
    public function getWorkOrderId(): string
    {
        return $this->workorderid;
    }

    /**
     * @param string $workOrderId
     *
     * @return void
     */
    public function setWorkOrderId(string $workOrderId): void
    {
        $this->workorderid = $workOrderId;
    }

    /**
     * @return string
     */
    public function getClientToken(): string
    {
        return $this->client_token;
    }

    /**
     * @param string $client_token
     *
     * @return void
     */
    public function setClientToken(string $clientToken): void
    {
        $this->client_token = $clientToken;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPreparedKey($key): string
    {
        $key = preg_replace(self::ADD_PAYDATA_REPLACEMENT_PATTERN, '$1', $key);

        return ucwords(str_replace('_', ' ', $key));
    }
}
