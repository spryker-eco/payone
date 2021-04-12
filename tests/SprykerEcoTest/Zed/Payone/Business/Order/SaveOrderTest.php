<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Payone\Persistence\Base\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\AbstractPayoneTest;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Order
 * @group SaveOrderTest
 */
class SaveOrderTest extends AbstractPayoneTest
{
    /**
     * @return void
     */
    public function testSaveOrder()
    {
        $checkoutResponseTransfer = $this->hydrateCheckoutResponseTransfer();
        $this->preparePayonePaymentTransfer();

        $this->payoneFacade->saveOrder($this->quoteTransfer, $checkoutResponseTransfer);

        $payoneEntity = SpyPaymentPayoneQuery::create()
            ->filterByFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->findOne();
        $paymentDetailEntity = $payoneEntity->getSpyPaymentPayoneDetail();

        $this->assertInstanceOf(SpyPaymentPayone::class, $payoneEntity);
        $this->assertEquals(PayoneApiConstants::PAYMENT_METHOD_INVOICE, $payoneEntity->getPaymentMethod());
        $this->assertNotEmpty($payoneEntity->getReference());

        $this->assertInstanceOf(SpyPaymentPayoneDetail::class, $paymentDetailEntity);
        $this->assertEquals('EUR', $paymentDetailEntity->getCurrency());
        $this->assertEquals('iban', $paymentDetailEntity->getIban());
        $this->assertEquals('bic', $paymentDetailEntity->getBic());
        $this->assertEquals('12345', $paymentDetailEntity->getAmount());
    }

    /**
     * @return void
     */
    public function testSaveOrderPayment()
    {
        $checkoutResponseTransfer = $this->hydrateCheckoutResponseTransfer();
        $this->preparePayonePaymentTransfer();

        $this->payoneFacade->saveOrderPayment($this->quoteTransfer, $checkoutResponseTransfer->getSaveOrder());

        $payoneEntity = SpyPaymentPayoneQuery::create()
            ->filterByFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->findOne();
        $paymentDetailEntity = $payoneEntity->getSpyPaymentPayoneDetail();

        $this->assertInstanceOf(SpyPaymentPayone::class, $payoneEntity);
        $this->assertEquals(PayoneApiConstants::PAYMENT_METHOD_INVOICE, $payoneEntity->getPaymentMethod());
        $this->assertNotEmpty($payoneEntity->getReference());

        $this->assertInstanceOf(SpyPaymentPayoneDetail::class, $paymentDetailEntity);
        $this->assertEquals('EUR', $paymentDetailEntity->getCurrency());
        $this->assertEquals('iban', $paymentDetailEntity->getIban());
        $this->assertEquals('bic', $paymentDetailEntity->getBic());
        $this->assertEquals('12345', $paymentDetailEntity->getAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function hydrateCheckoutResponseTransfer()
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())
            ->setSaveOrder(
                (new SaveOrderTransfer())
                    ->setIdSalesOrder($this->orderEntity->getIdSalesOrder())
            );

        return $checkoutResponseTransfer;
    }

    /**
     * @return void
     */
    protected function preparePayonePaymentTransfer()
    {
        $this->quoteTransfer->getPayment()
            ->getPayone()
            ->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_INVOICE)
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setPaymentDetail(
                (new PaymentDetailTransfer())
                    ->setAmount(12345)
                    ->setIban('iban')
                    ->setBic('bic')
                    ->setCurrency('EUR')
            );
    }
}
