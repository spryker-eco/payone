<?php

namespace SprykerEco\Zed\Payone\Dependency\Plugin;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface StandardParameterMapperPluginInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function map(AbstractRequestContainer $requestContainer, PayoneStandardParameterTransfer $standardParameter): AbstractRequestContainer;
}
