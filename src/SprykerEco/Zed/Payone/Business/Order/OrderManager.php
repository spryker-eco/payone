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
use Propel\Runtime\Propel;
use SprykerEco\Shared\Payone\PayoneTransactionStatusConstants;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;

class OrderManager implements OrderManagerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    private $config;

    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface
     */
    protected $payoneEntityManager;

    /**
     * @param \SprykerEco\Zed\Payone\PayoneConfig $config
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface $payoneEntityManager
     */
    public function __construct(
        PayoneConfig $config,
        PayoneEntityManagerInterface $payoneEntityManager
    ) {
        $this->config = $config;
        $this->payoneEntityManager = $payoneEntityManager;
    }

    /**
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\Order\OrderManager::saveOrderPayments} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->doSaveOrderPayments($quoteTransfer, $checkoutResponse->getSaveOrder());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->doSaveOrderPayments($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function doSaveOrderPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        // todo::maybe be good to use TransactionTrait
        Propel::getConnection()->beginTransaction();

        $paymentTransfer = $quoteTransfer->getPayment()->getPayone();
        $paymentTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $payment = $this->savePayment($paymentTransfer);

        $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();
        $this->savePaymentDetail($payment, $paymentDetailTransfer);
        $this->savePaymentPayoneOrderItems($saveOrderTransfer, $payment->getIdPaymentPayone());

        Propel::getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePaymentTransfer $paymentTransfer
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function savePayment(PayonePaymentTransfer $paymentTransfer)
    {
        $payment = new SpyPaymentPayone();
        $payment->fromArray(($paymentTransfer->toArray()));

        if ($payment->getReference() === null) {
            $orderEntity = $payment->getSpySalesOrder();
            $payment->setReference($this->config->generatePayoneReference($paymentTransfer, $orderEntity));
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
    protected function savePaymentDetail(SpyPaymentPayone $payment, PaymentDetailTransfer $paymentDetailTransfer)
    {
        $paymentDetailEntity = new SpyPaymentPayoneDetail();
        $paymentDetailEntity->setSpyPaymentPayone($payment);
        $paymentDetailEntity->fromArray($paymentDetailTransfer->toArray());
        $paymentDetailEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    protected function savePaymentPayoneOrderItems(SaveOrderTransfer $saveOrderTransfer, int $idSalesOrderItem): void
    {
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $paymentPayoneOrderItemTransfer = (new PaymentPayoneOrderItemTransfer())
                ->setIdPaymentPayone($idSalesOrderItem)
                ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
                ->setStatus(PayoneTransactionStatusConstants::STATUS_NEW);

            $this->payoneEntityManager->createPaymentPayoneOrderItem($paymentPayoneOrderItemTransfer);
        }
    }
}
