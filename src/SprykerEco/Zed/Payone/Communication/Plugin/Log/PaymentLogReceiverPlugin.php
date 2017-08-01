<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Log;

use Generated\Shared\Transfer\OrderCollectionTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacade getFacade()
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class PaymentLogReceiverPlugin extends AbstractPlugin implements PaymentLogReceiverPluginInterface
{

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        $orderCollectionTransfer = new OrderCollectionTransfer();
        $orderCollectionTransfer->setOrders($orders->getData());
        return $this->getFacade()->getPaymentLogs($orderCollectionTransfer);
    }

}
