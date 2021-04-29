<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\AbstractPayoneTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DummyAdapter;

/**
 * @property \SprykerEcoTest\Zed\Payone\PayoneZedTester $tester
 */
class PayoneFacadeExecutePostSaveHookTest extends AbstractPayoneTest
{
    protected const FAKE_REFUND_RESPONSE = '{"txid":"375461930","status":"APPROVED"}';

    /**
     * @return void
     */
    public function testExecutePostSaveHookApiLogWithoutError(): void
    {
        // Arrange
        $this->createPayonePayment();
        $this->createPayonePaymentDetail();
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();

        // Act
        $newCheckoutResponseTransfer = $this->createFacadeMock(
            new DummyAdapter(static::FAKE_REFUND_RESPONSE)
        )->executePostSaveHook($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertInstanceOf(CheckoutResponseTransfer::class, $newCheckoutResponseTransfer);
        $this->assertCount(0, $newCheckoutResponseTransfer->getErrors());
        $this->assertTrue($newCheckoutResponseTransfer->getIsExternalRedirect());
        $this->assertEquals('redirect url', $newCheckoutResponseTransfer->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testExecutePostSaveHookApiLogWithError(): void
    {
        // Arrange
        $this->createPayonePayment();
        $this->createPayonePaymentDetail();
        $this->createPayoneApiLogWithError(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();

        // Act
        $newCheckoutResponseTransfer = $this->createFacadeMock(
            new DummyAdapter(static::FAKE_REFUND_RESPONSE)
        )->executePostSaveHook($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertInstanceOf(CheckoutResponseTransfer::class, $newCheckoutResponseTransfer);
        $this->assertTrue($newCheckoutResponseTransfer->getIsExternalRedirect());
        $this->assertFalse($newCheckoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $newCheckoutResponseTransfer->getErrors());
        $this->assertEquals('any error', $newCheckoutResponseTransfer->getErrors()[0]->getMessage());
        $this->assertEquals('redirect url', $newCheckoutResponseTransfer->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testExecutePostSaveHookApiAuthorizationWithoutErrorAndWithRedirect(): void
    {
        // Arrange
        $this->createPayonePayment();
        $this->createPayonePaymentDetail();
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();

        // Act
        $newCheckoutResponseTransfer = $this->createFacadeMock(
            new DummyAdapter('{"txid":"375461930","status":"APPROVED","redirect_url":"http://test.com"}')
        )->executePostSaveHook($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertInstanceOf(CheckoutResponseTransfer::class, $newCheckoutResponseTransfer);
        $this->assertTrue($newCheckoutResponseTransfer->getIsExternalRedirect());
        $this->assertCount(0, $newCheckoutResponseTransfer->getErrors());
        $this->assertEquals('http://test.com', $newCheckoutResponseTransfer->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testExecutePostSaveHookApiAuthorizationWithError(): void
    {
        // Arrange
        $this->createPayonePayment();
        $this->createPayonePaymentDetail();
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();

        // Act
        $newCheckoutResponseTransfer = $this->createFacadeMock(
            new DummyAdapter('{"txid":"375461930","status":"FAILED","customer_message":"error customer message","error_code":"123"}')
        )->executePostSaveHook($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertInstanceOf(CheckoutResponseTransfer::class, $newCheckoutResponseTransfer);
        $this->assertFalse($newCheckoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $newCheckoutResponseTransfer->getErrors());
        $this->assertEquals('error customer message', $newCheckoutResponseTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);
        $this->quoteTransfer
            ->getPayment()
            ->getPayone()
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder());

        return $checkoutResponseTransfer;
    }
}
