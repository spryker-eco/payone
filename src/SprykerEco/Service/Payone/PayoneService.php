<?php

namespace SprykerEco\Service\Payone;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \SprykerEco\Service\Payone\PayoneServiceFactory getFactory()
 */
class PayoneService extends AbstractService implements PayoneServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function distributeOrderPrice(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()->createOrderPriceDistributor()->distributeOrderPrice($orderTransfer);
    }
}
