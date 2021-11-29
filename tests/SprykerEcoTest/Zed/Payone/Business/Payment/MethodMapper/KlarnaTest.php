<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\PayoneKlarnaBuilder;
use Generated\Shared\DataBuilder\PayoneKlarnaStartSessionRequestBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapper;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group KlarnaTest
 */
class KlarnaTest extends AbstractMethodMapperTest
{
    /**
     * @var string
     */
    protected const PAY_METHOD_TYPE = 'KIV';

    /**
     * @var string
     */
    protected const STANDARD_PARAMETER_CLEARING_TYPE = 'fnc';

    /**
     * @var array
     */
    protected const AUTHORIZATION_KLARNA_REQUIRED_PARAMS = [
        'financingtype' => self::PAY_METHOD_TYPE,
    ];

    /**
     * @var array
     */
    protected const PREAUTHORIZATION_KLARNA_REQUIRED_PARAMS = [
        'financingtype' => self::PAY_METHOD_TYPE,
    ];

    /**
     * @var array
     */
    protected const PREAUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
        'email' => self::DEFAULT_EMAIL,
    ];

    /**
     * @var array
     */
    protected const AUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @var array
     */
    public const PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
        'language' => self::STANDARD_PARAMETER_LANGUAGE,
        'email' => self::DEFAULT_EMAIL,
    ];

    /**
     * @var array
     */
    protected const START_SESSION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @var array
     */
    protected const START_SESSION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
        'language' => self::STANDARD_PARAMETER_LANGUAGE,
        'email' => self::DEFAULT_EMAIL,
    ];

    /**
     * @return void
     */
    public function testMapPaymentToPreauthorization(): void
    {
        // Arrange
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper($this->createKlarna());

        // Act
        $requestData = $paymentMethodMapper->mapKlarnaPaymentToPreAuthorization($paymentEntity, $this->getQuote())->toArray();

        // Assert
        foreach (static::PREAUTHORIZATION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_KLARNA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToCapture(): void
    {
        // Arrange
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper($this->createKlarna());

        // Act
        $requestData = $paymentMethodMapper->mapPaymentToCapture($paymentEntity)->toArray();

        // Assert
        foreach (static::CAPTURE_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToStartSession(): void
    {
        // Arrange
        $payoneKlarnaStartSessionRequest = $this->getPayoneKlarnaStartSessionRequest();
        $paymentMethodMapper = $this->preparePaymentMethodMapper($this->createKlarna());

        // Act
        $requestData = $paymentMethodMapper->mapPaymentToKlarnaGenericPaymentContainer($payoneKlarnaStartSessionRequest)->toArray();

        // Assert
        foreach (static::START_SESSION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::START_SESSION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapper
     */
    protected function createKlarna(): KlarnaPaymentMapper
    {
        return new KlarnaPaymentMapper($this->getStoreConfigMock(), $this->getRequestStackMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function getCurrentRequestMock(): Request
    {
        $mock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['getClientIp'])
            ->getMock();

        $mock->method('getClientIp')->willReturn(static::CLIENT_IP);

        return $mock;
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer
     */
    protected function getPayoneKlarnaStartSessionRequest(): PayoneKlarnaStartSessionRequestTransfer
    {
        $payoneKlarnaStartSessionRequestBuilder = new PayoneKlarnaStartSessionRequestBuilder([
            'quote' => $this->getQuote(),
            'payMethod' => static::PAY_METHOD_TYPE,
        ]);

        return $payoneKlarnaStartSessionRequestBuilder->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote(): QuoteTransfer
    {
        $payoneKlarnaBuilder = new PayoneKlarnaBuilder([
            'payMethodToken' => 'token',
            'payMethod' => static::PAY_METHOD_TYPE,
        ]);

        $paymentBuilder = new PaymentBuilder([
            'payoneKlarna' => $payoneKlarnaBuilder->build(),
        ]);

        $quoteBuilder = new QuoteBuilder([
            'totals' => $this->getTotals(),
            'currency' => $this->createCurrency(),
            'billingAddress' => $this->getAddressMock(),
            'payment' => $paymentBuilder->build(),
        ]);

        return $quoteBuilder->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function createCurrency(): CurrencyTransfer
    {
        $currency = new CurrencyTransfer();
        $currency->setCode(static::STANDARD_PARAMETER_CURRENCY);

        return $currency;
    }
}
