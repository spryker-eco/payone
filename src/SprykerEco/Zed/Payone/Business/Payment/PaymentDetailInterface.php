<?php

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PaymentDetailTransfer;

interface PaymentDetailInterface
{
    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDetailTransfer
     */
    public function getPaymentDetail(int $idOrder): PaymentDetailTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentDetailTransfer $paymentDataTransfer
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDetailTransfer $paymentDataTransfer, $idOrder): void;
}
