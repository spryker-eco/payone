<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Dependency\Facade;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class PayoneToRefundBridge implements PayoneToRefundInterface
{
    /**
     * @var \Spryker\Zed\Refund\Business\RefundFacadeInterface
     */
    protected $refundFacade;

    /**
     * @param \Spryker\Zed\Refund\Business\RefundFacadeInterface $refundFacade
     */
    public function __construct($refundFacade)
    {
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param array $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(array $orderItems, SpySalesOrder $orderEntity)
    {
        return $this->refundFacade->calculateRefund($orderItems, $orderEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return bool
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        return $this->refundFacade->saveRefund($refundTransfer);
    }
}
