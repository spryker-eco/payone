<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business\Facade;

use Functional\SprykerEco\Zed\Payone\Business\AbstractBusinessTest;
use Functional\SprykerEco\Zed\Payone\Business\Api\Adapter\SetExpressCheckoutAdapterMock;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Handler\PayoneHandler;

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
        $requestTransfer = new PayoneInitPaypalExpressCheckoutRequestTransfer();

        $quote = clone $this->quoteTransfer;

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider(PayoneHandler::PAYMENT_PROVIDER);
        $paypalExpressCheckoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressCheckoutPayment);
        $quote->setPayment($paymentTransfer);

        $requestTransfer->setQuote($quote);
        $requestTransfer->setSuccessUrl(
            Config::get(PayoneConstants::PAYONE)[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_SUCCESS_URL]
        );
        $requestTransfer->setFailureUrl(
            Config::get(PayoneConstants::PAYONE)[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_FAILURE_URL]
        );
        $requestTransfer->setBackUrl(
            Config::get(PayoneConstants::PAYONE)[PayoneConstants::PAYONE_REDIRECT_EXPRESS_CHECKOUT_BACK_URL]
        );

        return $requestTransfer;
    }

}