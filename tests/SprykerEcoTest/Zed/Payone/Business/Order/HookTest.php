<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\AbstractPayoneTest;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Order
 * @group HookTest
 */
class HookTest extends AbstractPayoneTest
{
    /**
     * @return void
     */
    public function testPostSaveHook()
    {
        $this->createPayonePayment();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $this->quoteTransfer->getPayment()
            ->getPayone()
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder());
        $newCheckoutResponseTransfer = $this->payoneFacade->postSaveHook($this->quoteTransfer, $checkoutResponseTransfer);

        $this->assertInstanceOf(CheckoutResponseTransfer::class, $newCheckoutResponseTransfer);
        $this->assertTrue($newCheckoutResponseTransfer->getIsExternalRedirect());
        $this->assertEquals('redirect url', $newCheckoutResponseTransfer->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testPostSaveHookDoesNotSendPreauthorizeRequestTwice(): void
    {
        $this->createPayonePayment();
        $paymentApiLog = $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $this->quoteTransfer->getPayment()
            ->getPayone()
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder());
        $newCheckoutResponseTransfer = $this->payoneFacade->postSaveHook($this->quoteTransfer, $checkoutResponseTransfer);

        $preauthorizedRequestsCount = (new SpyPaymentPayoneApiLogQuery())->filterByTransactionId(static::TRANSACTION_ID)->find()->count();
        $this->assertTrue($newCheckoutResponseTransfer->getIsExternalRedirect());
        $this->assertEquals(1, $preauthorizedRequestsCount);
    }
}
