<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class PaydataContainer extends AbstractContainer
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var string|null
     */
    protected $authorization_token;

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return void
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationToken(): ?string
    {
        return $this->authorization_token;
    }

    /**
     * @param string|null $authorizationToken
     *
     * @return void
     */
    public function setAuthorizationToken(?string $authorizationToken): void
    {
        $this->authorization_token = $authorizationToken;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPreparedKey(string $key): string
    {
        $preparedKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
        $template = 'add_paydata[KEY]';

        return str_replace('KEY', $preparedKey, $template);
    }
}
