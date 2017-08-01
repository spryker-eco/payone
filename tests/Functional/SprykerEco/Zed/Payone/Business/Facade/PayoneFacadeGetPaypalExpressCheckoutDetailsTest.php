<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business\Facade;

use Functional\SprykerEco\Zed\Payone\Business\AbstractBusinessTest;
use Functional\SprykerEco\Zed\Payone\Business\Api\Adapter\GetExpressCheckoutAdapterMock;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use SprykerEco\Yves\Payone\Handler\PayoneHandler;

class PayoneFacadeGetPaypalExpressCheckoutDetailsTest extends AbstractBusinessTest
{

    /**
     * @return void
     */
    public function testGetPaypalExpressCheckoutDetailsWithSuccessResponse()
    {
        $adapter = new GetExpressCheckoutAdapterMock();
        $facadeMock = $this->getFacadeMock($adapter);
        $response = $facadeMock->getPaypalExpressCheckoutDetails($this->getFilledQuote());

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer',
            $response
        );
        $this->assertNotEmpty($response->getEmail());
        $this->assertNotEmpty($response->getShippingFirstName());
        $this->assertNotEmpty($response->getShippingLastName());
        $this->assertNotEmpty($response->getShippingStreet());
        $this->assertNotEmpty($response->getShippingZip());
        $this->assertNotEmpty($response->getShippingCity());
        $this->assertNotEmpty($response->getShippingCountry());
    }

    /**
     * @return void
     */
    public function testGetPaypalExpressCheckoutDetailsWithFailureResponse()
    {
        $adapter = new GetExpressCheckoutAdapterMock();
        $adapter->setExpectSuccess(false);
        $facadeMock = $this->getFacadeMock($adapter);
        $response = $facadeMock->getPaypalExpressCheckoutDetails($this->getFilledQuote());

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer',
            $response
        );
        $this->assertNotEmpty($response->getErrorCode());
        $this->assertNotEmpty($response->getErrorMessage());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getFilledQuote()
    {
        $quote = clone $this->quoteTransfer;

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider(PayoneHandler::PAYMENT_PROVIDER);
        $paypalExpressCheckoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paypalExpressCheckoutPayment->setWorkOrderId('WX1A1SE57Y8D1XNR');
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressCheckoutPayment);
        $quote->setPayment($paymentTransfer);

        return $quote;
    }

}
