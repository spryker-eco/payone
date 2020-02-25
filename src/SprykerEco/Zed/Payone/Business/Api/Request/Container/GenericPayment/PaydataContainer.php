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
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPreparedKey($key)
    {
        $preparedKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
        $template = "add_paydata[KEY]";

        return str_replace('KEY', $preparedKey, $template);
    }
}
