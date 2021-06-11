<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface;

interface PaymentMethodMapperInterface extends BasePaymentMethodMapperInterface
{
    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer): AbstractAuthorizationContainer;

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity): PreAuthorizationContainerInterface;

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity): CaptureContainerInterface;

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity): DebitContainerInterface;

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity): RefundContainerInterface;
}
