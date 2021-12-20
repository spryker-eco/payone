<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\DataBuilder\PayoneInitPaypalExpressCheckoutRequestBuilder;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\SetExpressCheckoutAdapterMock;

class PayoneFacadeInitExpressCheckoutTest extends AbstractBusinessTest
{
    /**
     * @return void
     */
    public function testInitPaypalExpressCheckoutWithSuccessResponse(): void
    {
        $adapter = new SetExpressCheckoutAdapterMock();
        $facadeMock = $this->createFacadeMock($adapter);
        $requestTransfer = $this->createExpressCheckoutRequestTransfer();
        $response = $facadeMock->initPaypalExpressCheckout($requestTransfer);

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer',
            $response,
        );

        $this->assertNotEmpty($response->getWorkOrderId());
        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertEmpty($response->getErrorCode());
        $this->assertEmpty($response->getErrorMessage());
        $this->assertEquals(
            $response->getStatus(),
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT,
        );
    }

    /**
     * @return void
     */
    public function testInitPaypalExpressCheckoutWithFailureResponse(): void
    {
        $adapter = new SetExpressCheckoutAdapterMock();
        $adapter->setExpectSuccess(false);
        $facadeMock = $this->createFacadeMock($adapter);

        $requestTransfer = $this->createExpressCheckoutRequestTransfer();
        $response = $facadeMock->initPaypalExpressCheckout($requestTransfer);

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer',
            $response,
        );

        $this->assertNotEmpty($response->getErrorCode());
        $this->assertNotEmpty($response->getErrorMessage());
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer
     */
    protected function createExpressCheckoutRequestTransfer(): PayoneInitPaypalExpressCheckoutRequestTransfer
    {
        $requestTransfer = (new PayoneInitPaypalExpressCheckoutRequestBuilder())->build();

        $quote = clone $this->quoteTransfer;

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider(PayoneConfig::PROVIDER_NAME);
        $paypalExpressCheckoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paypalExpressCheckoutPayment->setWorkOrderId('WX1A1SE57Y8D1XNR');
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressCheckoutPayment);
        $quote->setPayment($paymentTransfer);

        $requestTransfer->setQuote($quote);

        return $requestTransfer;
    }
}
