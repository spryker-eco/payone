<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;

class KlarnaPreAuthorizationContainer extends AbstractPayoneKlarnaAuthorizationContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION;

    /**
     * @var string|null
     */
    protected $workorderid;

    /**
     * @param string $workorderId
     *
     * @return void
     */
    public function setWorkOrderId(string $workorderId): void
    {
        $this->workorderid = $workorderId;
    }

    /**
     * @return string|null
     */
    public function getWorkOrderId(): ?string
    {
        return $this->workorderid;
    }
}
