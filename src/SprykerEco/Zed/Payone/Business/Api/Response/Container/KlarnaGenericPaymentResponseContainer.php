<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class KlarnaGenericPaymentResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected const ADD_PAYDATA_REPLACEMENT_PATTERN = '/add_paydata\[(.*)\]/';

    /**
     * @var string|null
     */
    protected $workorderid;

    /**
     * @var string|null
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
    public function getWorkOrderId(): ?string
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
     * @return string|null
     */
    public function getClientToken(): ?string
    {
        return $this->client_token;
    }

    /**
     * @param string $clientToken
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
    protected function getPreparedKey(string $key): string
    {
        $key = preg_replace(static::ADD_PAYDATA_REPLACEMENT_PATTERN, '$1', $key);

        return ucwords(str_replace('_', ' ', $key));
    }
}
