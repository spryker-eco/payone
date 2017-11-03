<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;

class PreAuthorizationContainer extends AbstractAuthorizationContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION;

    /**
     * @var string
     */
    protected $workorderid;

    /**
     * @param string $workorderid
     *
     * @return void
     */
    public function setWorkOrderId($workorderid)
    {
        $this->workorderid = $workorderid;
    }

    /**
     * @return string
     */
    public function getWorkOrderId()
    {
        return $this->workorderid;
    }
}
