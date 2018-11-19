<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use SprykerEco\Zed\Payone\Business\PayoneFacade;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEcoTest\Zed\Payone\Business\AbstractPayoneTest;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group FilterPaymentMethod
 */
class FilterPaymentMethodTest extends AbstractPayoneTest
{
    public const GREEN_RESULT_PAYMENT_METHODS = [
        0 => PayoneConfig::PAYMENT_METHOD_CREDIT_CARD,
        1 => PayoneConfig::PAYMENT_METHOD_INVOICE,
    ];

    public const YELLOW_RESULT_PAYMENT_METHODS = [
        0 => PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER,
    ];

    public const RED_RESULT_PAYMENT_METHODS = [
        0 => PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT,
    ];

    public const UNKNOWN_RESULT_PAYMENT_METHODS = [
        0 => PayoneConfig::PAYMENT_METHOD_CREDIT_CARD,
        1 => PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER,
        2 => PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT,
    ];

    /**
     * @return void
     */
    public function testFilterPaymentMethod(): void
    {
        $facade = new PayoneFacade();

        $this->quoteTransfer->setConsumerScore('G');
        $greenResult = $facade->filterPaymentMethods($this->preparePaymentMethodsTransfer(), $this->quoteTransfer)->getMethods();

        foreach ($greenResult as $key => $transfer) {
            $this->assertEquals(static::GREEN_RESULT_PAYMENT_METHODS[$key], $transfer->getMethodName());
        }

        $this->quoteTransfer->setConsumerScore('Y');
        $yellowResult = $facade->filterPaymentMethods($this->preparePaymentMethodsTransfer(), $this->quoteTransfer)->getMethods();

        foreach ($yellowResult as $key => $transfer) {
            $this->assertEquals(static::YELLOW_RESULT_PAYMENT_METHODS[$key], $transfer->getMethodName());
        }

        $this->quoteTransfer->setConsumerScore('R');
        $redResult = $facade->filterPaymentMethods($this->preparePaymentMethodsTransfer(), $this->quoteTransfer)->getMethods();

        foreach ($redResult as $key => $transfer) {
            $this->assertEquals(static::RED_RESULT_PAYMENT_METHODS[$key], $transfer->getMethodName());
        }

        $this->quoteTransfer->setConsumerScore('U');
        $unknownResult = $facade->filterPaymentMethods($this->preparePaymentMethodsTransfer(), $this->quoteTransfer)->getMethods();

        foreach ($unknownResult as $key => $transfer) {
            $this->assertEquals(static::UNKNOWN_RESULT_PAYMENT_METHODS[$key], $transfer->getMethodName());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function preparePaymentMethodsTransfer(): PaymentMethodsTransfer
    {
        $paymentMethodsTransfer = new PaymentMethodsTransfer();

        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_CREDIT_CARD));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_E_WALLET));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_DIRECT_DEBIT));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_INSTANT_ONLINE_TRANSFER));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_GIROPAY_ONLINE_TRANSFER));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_IDEAL_ONLINE_TRANSFER));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_POSTFINANCE_CARD_ONLINE_TRANSFER));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_PRZELEWY24_ONLINE_TRANSFER));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_BANCONTACT_ONLINE_TRANSFER));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_INVOICE));
        $paymentMethodsTransfer->addMethod($this->preparePaymentMethod(PayoneConfig::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT));

        return $paymentMethodsTransfer;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function preparePaymentMethod(string $name): PaymentMethodTransfer
    {
        $paymentMethodTransfer = new PaymentMethodTransfer();
        $paymentMethodTransfer->setMethodName($name);

        return $paymentMethodTransfer;
    }
}
