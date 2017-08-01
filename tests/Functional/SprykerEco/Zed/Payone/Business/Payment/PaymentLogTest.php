<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business\Payment;

use Functional\SprykerEco\Zed\Payone\Business\AbstractPayoneTest;
use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group PaymentLogTest
 */
class PaymentLogTest extends AbstractPayoneTest
{

    /**
     * @return void
     */
    public function testGetPaymentLogs()
    {
        $apiLogs = [];
        $this->createPayonePayment();
        $apiLogs[] = $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $apiLogs[] = $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_REDIRECT);
        $apiLogs[] = $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_CAPTURE, PayoneApiConstants::RESPONSE_TYPE_TIMEOUT);

        $orderCollection = (new OrderCollectionTransfer())
            ->addOrders($this->orderTransfer);
        $paymentLogCollectionTransfer = $this->payoneFacade->getPaymentLogs($orderCollection);

        $this->assertInstanceOf(PayonePaymentLogCollectionTransfer::class, $paymentLogCollectionTransfer);
        $this->assertEquals(count($apiLogs), $paymentLogCollectionTransfer->getPaymentLogs()->count());

        foreach ($paymentLogCollectionTransfer->getPaymentLogs() as $key => $paymentLog) {
            $this->assertEquals($apiLogs[$key]->getTransactionId(), $paymentLog->getTransactionId());
            $this->assertEquals($apiLogs[$key]->getStatus(), $paymentLog->getStatus());
            $this->assertEquals($apiLogs[$key]->getRequest(), $paymentLog->getRequest());
        }
    }

    /**
     * @return void
     */
    public function testGetPaymentLogsEmpty()
    {
        $this->createPayonePayment();

        $orderCollection = (new OrderCollectionTransfer())
            ->addOrders($this->orderTransfer);
        $paymentLogCollectionTransfer = $this->payoneFacade->getPaymentLogs($orderCollection);

        $this->assertInstanceOf(PayonePaymentLogCollectionTransfer::class, $paymentLogCollectionTransfer);
        $this->assertEquals(0, $paymentLogCollectionTransfer->getPaymentLogs()->count());
    }

}
