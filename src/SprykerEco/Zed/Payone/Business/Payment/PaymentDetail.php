<?php

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PaymentDetailTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PaymentDetail implements PaymentDetailInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     */
    public function __construct(PayoneQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail(int $idOrder): PaymentDetailTransfer
    {
        $paymentEntity = $this->queryContainer->createPaymentByOrderId($idOrder)->findOne();
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();
        $paymentDetailTransfer = new PaymentDetailTransfer();
        $paymentDetailTransfer->fromArray($paymentDetailEntity->toArray(), true);

        return $paymentDetailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentDetailTransfer $paymentDataTransfer
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDetailTransfer $paymentDataTransfer, $idOrder): void
    {
        $paymentEntity = $this->queryContainer->createPaymentByOrderId($idOrder)->findOne();
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $paymentDetailEntity->fromArray($paymentDataTransfer->toArray());

        $paymentDetailEntity->save();
    }
}
