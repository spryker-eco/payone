<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Adapter;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface AdapterInterface
{

    /**
     * @param array $params
     *
     * @return array
     */
    public function sendRawRequest(array $params);

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return array
     */
    public function sendRequest(AbstractRequestContainer $container);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getRawResponse();

}
