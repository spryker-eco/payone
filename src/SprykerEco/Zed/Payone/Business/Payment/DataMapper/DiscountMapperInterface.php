<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface DiscountMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $discountContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function prepareDiscount($discountContainer, AbstractRequestContainer $container): AbstractRequestContainer;
}
