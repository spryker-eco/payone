<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payone\Business\Payment;

use SprykerTest\Zed\Payone\Business\AbstractPayoneTest;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetailQuery;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group PaymentDetailTest
 */
class PaymentDetailTest extends AbstractPayoneTest
{

    /**
     * @return void
     */
    public function testGetPaymentDetail()
    {
        $this->createPayonePayment();
        $spyPaymentDetail = $this->createPayonePaymentDetail('12345', '123');
        $paymentDetailTransfer = $this->payoneFacade->getPaymentDetail($this->orderEntity->getIdSalesOrder());

        $this->assertInstanceOf(PaymentDetailTransfer::class, $paymentDetailTransfer);
        $this->assertEquals($spyPaymentDetail->getAmount(), $paymentDetailTransfer->getAmount());
        $this->assertEquals($spyPaymentDetail->getCurrency(), $paymentDetailTransfer->getCurrency());
        $this->assertEquals($spyPaymentDetail->getBic(), $paymentDetailTransfer->getBic());
        $this->assertEquals($spyPaymentDetail->getIban(), $paymentDetailTransfer->getIban());
    }

    /**
     * @return void
     */
    public function testUpdatePaymentDetail()
    {
        $this->createPayonePayment();
        $spyPaymentDetailOld = $this->createPayonePaymentDetail('12345', '123');

        $paymentDetailTransfer = (new PaymentDetailTransfer())
            ->setAmount(99999)
            ->setCurrency('USD')
            ->setIban('777777')
            ->setBic('9999')
            ->setInvoiceTitle('new title');

        $this->payoneFacade->updatePaymentDetail($paymentDetailTransfer, $this->orderEntity->getIdSalesOrder());
        $spyPaymentDetailNew = (new SpyPaymentPayoneDetailQuery())
            ->findOneByIdPaymentPayone($this->spyPaymentPayone->getIdPaymentPayone());

        $this->assertInstanceOf(SpyPaymentPayoneDetail::class, $spyPaymentDetailNew);
        $this->assertNotEquals($spyPaymentDetailOld, $spyPaymentDetailNew);
        $this->assertEquals($paymentDetailTransfer->getAmount(), $spyPaymentDetailNew->getAmount());
        $this->assertEquals($paymentDetailTransfer->getCurrency(), $spyPaymentDetailNew->getCurrency());
        $this->assertEquals($paymentDetailTransfer->getBic(), $spyPaymentDetailNew->getBic());
        $this->assertEquals($paymentDetailTransfer->getIban(), $spyPaymentDetailNew->getIban());
        $this->assertEquals($paymentDetailTransfer->getInvoiceTitle(), $spyPaymentDetailNew->getInvoiceTitle());
    }

}
