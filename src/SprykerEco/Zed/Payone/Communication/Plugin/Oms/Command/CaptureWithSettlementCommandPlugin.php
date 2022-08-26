<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\PayoneCaptureTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;

/**
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
 */
class CaptureWithSettlementCommandPlugin extends AbstractPayonePlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `PayoneCaptureTransfer.order` to be set.
     * - Requires `PayoneCaptureTransfer.payment.fkSalesOrder` to be set.
     *
     * @api
     *
     * @param array $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $captureTransfer = new PayoneCaptureTransfer();

        $paymentTransfer = new PayonePaymentTransfer();
        $paymentTransfer->setFkSalesOrder($orderEntity->getSpyPaymentPayones()->getFirst()->getFkSalesOrder());
        $captureTransfer->setPayment($paymentTransfer);

        $orderTransfer = $this->getOrderTransfer($orderEntity);
        $captureTransfer->setAmount($orderTransfer->getTotals()->getGrandTotal());
        $captureTransfer->setSettleaccount(PayoneApiConstants::SETTLE_ACCOUNT_YES);
        $captureTransfer->setOrder($orderTransfer);

        $this->getFacade()->capturePayment($captureTransfer);

        return [];
    }
}
