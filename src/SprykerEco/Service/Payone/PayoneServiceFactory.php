<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Payone;

use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerEco\Service\Payone\Model\Distributor\OrderPriceDistributor;
use SprykerEco\Service\Payone\Model\Distributor\OrderPriceDistributorInterface;

class PayoneServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SprykerEco\Service\Payone\Model\Distributor\OrderPriceDistributorInterface
     */
    public function createOrderPriceDistributor(): OrderPriceDistributorInterface
    {
        return new OrderPriceDistributor();
    }
}
