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
    public function getWorkOrderId()
    {
        return $this->workorderid;
    }

    /**
     * @param string $workOrderId
     *
     * @return void
     */
    public function setWorkOrderId(string $workOrderId)
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
     */
    public function setClientToken(string $client_token): void
    {
        $this->client_token = $client_token;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPreparedKey($key)
    {
        $key = preg_replace('/add_paydata\[(.*)\]/', '$1', $key);

        return ucwords(str_replace('_', ' ', $key));
    }
}
