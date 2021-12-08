<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Payment\MethodMapper;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group InvoiceTest
 */
class InvoiceTest extends AbstractMethodMapperTest
{
    /**
     * @var string
     */
    public const STANDARD_PARAMETER_CLEARING_TYPE = 'rec';

    /**
     * @var array
     */
    public const AUTHORIZATION_INVOICE_REQUIRED_PARAMS = [
    ];

    /**
     * @var array
     */
    public const PREAUTHORIZATION_INVOICE_REQUIRED_PARAMS = [
    ];

    /**
     * @var array
     */
    public const PREAUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @var array
     */
    public const AUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @return void
     */
    public function testMapPaymentToPreauthorization(): void
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new Invoice($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity)->toArray();

        foreach (static::PREAUTHORIZATION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_INVOICE_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToAuthorization(): void
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new Invoice($this->getStoreConfigMock()));

        $orderTransfer = $this->getSalesOrderTransfer();

        $requestData = $paymentMethodMapper->mapPaymentToAuthorization($paymentEntity, $orderTransfer)->toArray();

        foreach (static::AUTHORIZATION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::AUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::AUTHORIZATION_INVOICE_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToCapture(): void
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new Invoice($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToCapture($paymentEntity)->toArray();

        foreach (static::CAPTURE_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToRefund(): void
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new Invoice($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToRefund($paymentEntity)->toArray();

        foreach (static::REFUND_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertEquals($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToDebit(): void
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new Invoice($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToDebit($paymentEntity)->toArray();

        foreach (static::DEBIT_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail
     */
    protected function getPaymentPayoneDetailMock(): SpyPaymentPayoneDetail
    {
        $paymentPayoneDetail = parent::getPaymentPayoneDetailMock();

        return $paymentPayoneDetail;
    }
}
