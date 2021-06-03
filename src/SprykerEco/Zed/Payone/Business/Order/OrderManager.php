<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentPayoneOrderItemTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Shared\Payone\PayoneTransactionStatusConstants;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;

class OrderManager implements OrderManagerInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected $payoneConfig;

    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface
     */
    protected $payoneEntityManager;

    /**
     * @param \SprykerEco\Zed\Payone\PayoneConfig $payoneConfig
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface $payoneEntityManager
     */
    public function __construct(
        PayoneConfig $payoneConfig,
        PayoneEntityManagerInterface $payoneEntityManager
    ) {
        $this->payoneConfig = $payoneConfig;
        $this->payoneEntityManager = $payoneEntityManager;
    }

    /**
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\Order\OrderManager::saveOrderPayment()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void
    {
        $this->doSaveOrderPayment($quoteTransfer, $checkoutResponse->getSaveOrder());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->doSaveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function doSaveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== PayoneConfig::PROVIDER_NAME) {
            return;
        }

        $quoteTransfer->getPayment()->requirePayone();
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $saveOrderTransfer): void {
            $paymentTransfer = $quoteTransfer->getPayment()->getPayone();
            $paymentTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
            $paymentPayoneEntity = $this->savePayment($paymentTransfer);

            $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();
            $this->savePaymentDetail($paymentPayoneEntity, $paymentDetailTransfer);
            $this->savePaymentPayoneOrderItems($saveOrderTransfer, $paymentPayoneEntity->getIdPaymentPayone());
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePaymentTransfer $paymentTransfer
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function savePayment(PayonePaymentTransfer $paymentTransfer): SpyPaymentPayone
    {
        $payment = new SpyPaymentPayone();
        $payment->fromArray(($paymentTransfer->toArray()));

        if ($payment->getReference() === null) {
            $orderEntity = $payment->getSpySalesOrder();
            $payment->setReference($this->payoneConfig->generatePayoneReference($paymentTransfer, $orderEntity));
        }

        $payment->save();

        return $payment;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $payment
     * @param \Generated\Shared\Transfer\PaymentDetailTransfer $paymentDetailTransfer
     *
     * @return void
     */
    protected function savePaymentDetail(SpyPaymentPayone $payment, PaymentDetailTransfer $paymentDetailTransfer): void
    {
        $paymentDetailEntity = new SpyPaymentPayoneDetail();
        $paymentDetailEntity->setSpyPaymentPayone($payment);
        $paymentDetailEntity->fromArray($paymentDetailTransfer->toArray());
        $paymentDetailEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param int $idPaymentPayone
     *
     * @return void
     */
    protected function savePaymentPayoneOrderItems(SaveOrderTransfer $saveOrderTransfer, int $idPaymentPayone): void
    {
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $paymentPayoneOrderItemTransfer = (new PaymentPayoneOrderItemTransfer())
                ->setIdPaymentPayone($idPaymentPayone)
                ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
                ->setStatus(PayoneTransactionStatusConstants::STATUS_NEW);

            $this->payoneEntityManager->createPaymentPayoneOrderItem($paymentPayoneOrderItemTransfer);
        }
    }
}
