<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\DataBuilder\PayoneInitPaypalExpressCheckoutRequestBuilder;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\SetExpressCheckoutAdapterMock;

class PayoneFacadeInitExpressCheckoutTest extends AbstractBusinessTest
{
    /**
     * @return void
     */
    public function testInitPaypalExpressCheckoutWithSuccessResponse()
    {
        $adapter = new SetExpressCheckoutAdapterMock();
        $facadeMock = $this->getFacadeMock($adapter);
        $requestTransfer = $this->createExpressCheckoutRequestTransfer();
        $response = $facadeMock->initPaypalExpressCheckout($requestTransfer);

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer',
            $response
        );

        $this->assertNotEmpty($response->getWorkOrderId());
        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertEmpty($response->getErrorCode());
        $this->assertEmpty($response->getErrorMessage());
        $this->assertEquals(
            $response->getStatus(),
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT
        );
    }

    /**
     * @return void
     */
    public function testInitPaypalExpressCheckoutWithFailureResponse()
    {
        $adapter = new SetExpressCheckoutAdapterMock();
        $adapter->setExpectSuccess(false);
        $facadeMock = $this->getFacadeMock($adapter);

        $requestTransfer = $this->createExpressCheckoutRequestTransfer();
        $response = $facadeMock->initPaypalExpressCheckout($requestTransfer);

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer',
            $response
        );

        $this->assertNotEmpty($response->getErrorCode());
        $this->assertNotEmpty($response->getErrorMessage());
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer
     */
    protected function createExpressCheckoutRequestTransfer()
    {
        $requestTransfer = (new PayoneInitPaypalExpressCheckoutRequestBuilder())->build();

        $quote = clone $this->quoteTransfer;

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider('Payone');
        $paypalExpressCheckoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressCheckoutPayment);
        $quote->setPayment($paymentTransfer);

        $requestTransfer->setQuote($quote);

        return $requestTransfer;
    }
}
