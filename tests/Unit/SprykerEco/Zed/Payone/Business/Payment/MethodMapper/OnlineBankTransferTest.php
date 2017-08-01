<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group OnlineBankTransferTest
 */
class OnlineBankTransferTest extends AbstractMethodMapperTest
{

    const STANDARD_PARAMETER_CLEARING_TYPE = 'sb';
    const ONLINE_BANK_TRANSFER_TYPE = 'PNT';

    const AUTHORIZATION_ONLINE_BANK_TRANSFER_REQUIRED_PARAMS = [
        'onlinebanktransfertype' => self::ONLINE_BANK_TRANSFER_TYPE,
    ];

    const PREAUTHORIZATION_ONLINE_BANK_TRANSFER_REQUIRED_PARAMS = [
        'onlinebanktransfertype' => self::ONLINE_BANK_TRANSFER_TYPE,
    ];

    const PREAUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    const AUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @return void
     */
    public function testMapPaymentToPreauthorization()
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new OnlineBankTransfer($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity)->toArray();

        foreach (static::PREAUTHORIZATION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_ONLINE_BANK_TRANSFER_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToAuthorization()
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new OnlineBankTransfer($this->getStoreConfigMock()));

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

        foreach (static::AUTHORIZATION_ONLINE_BANK_TRANSFER_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToCapture()
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new OnlineBankTransfer($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToCapture($paymentEntity)->toArray();

        foreach (static::CAPTURE_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToRefund()
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new OnlineBankTransfer($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToRefund($paymentEntity)->toArray();

        foreach (static::REFUND_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToDebit()
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new OnlineBankTransfer($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToDebit($paymentEntity)->toArray();

        foreach (static::DEBIT_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail
     */
    protected function getPaymentPayoneDetailMock()
    {
        $paymentPayoneDetail = parent::getPaymentPayoneDetailMock();

        $paymentPayoneDetail->method('getType')->willReturn(static::ONLINE_BANK_TRANSFER_TYPE);

        return $paymentPayoneDetail;
    }

}
