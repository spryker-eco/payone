<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group CreditCardPseudoTest
 */
class CreditCardPseudoTest extends AbstractMethodMapperTest
{
    public const PSEUDO_CARD_PAN = '1234567890123456';
    public const CARD_TYPE = 'V';
    public const CARD_EXPIRE_DATE = '1609';
    public const STANDARD_PARAMETER_CLEARING_TYPE = 'cc';

    public const AUTHORIZATION_CREDIT_CARD_PSEUDO_REQUIRED_PARAMS = [
        'pseudocardpan' => self::PSEUDO_CARD_PAN,
    ];

    public const PREAUTHORIZATION_CREDIT_CARD_PSEUDO_REQUIRED_PARAMS = [
        'pseudocardpan' => self::PSEUDO_CARD_PAN,
    ];

    public const CREDIT_CARD_CHECK_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'cardpan' => self::PSEUDO_CARD_PAN,
        'cardtype' => self::CARD_TYPE,
        'cardexpiredate' => self::CARD_EXPIRE_DATE,
    ];

    public const PREAUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

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
    public function testMapPaymentToPreauthorization()
    {
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new CreditCardPseudo($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity)->toArray();

        foreach (static::PREAUTHORIZATION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_CREDIT_CARD_PSEUDO_REQUIRED_PARAMS as $key => $value) {
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
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new CreditCardPseudo($this->getStoreConfigMock()));

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

        foreach (static::AUTHORIZATION_CREDIT_CARD_PSEUDO_REQUIRED_PARAMS as $key => $value) {
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
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new CreditCardPseudo($this->getStoreConfigMock()));

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
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new CreditCardPseudo($this->getStoreConfigMock()));

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
        $paymentMethodMapper = $this->preparePaymentMethodMapper(new CreditCardPseudo($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapPaymentToDebit($paymentEntity)->toArray();

        foreach (static::DEBIT_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapCreditCardCheck()
    {
        $creditCardTransfer = new PayoneCreditCardTransfer();
        $creditCardTransfer->setAid(static::STANDARD_PARAMETER_AID);
        $creditCardTransfer->setCardPan(static::PSEUDO_CARD_PAN);
        $creditCardTransfer->setCardType(static::CARD_TYPE);
        $creditCardTransfer->setCardExpireDate(static::CARD_EXPIRE_DATE);

        $paymentMethodMapper = $this->preparePaymentMethodMapper(new CreditCardPseudo($this->getStoreConfigMock()));

        $requestData = $paymentMethodMapper->mapCreditCardCheck($creditCardTransfer)->toArray();

        foreach (static::CREDIT_CARD_CHECK_REQUIRED_PARAMS as $key => $value) {
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
        $paymentPayoneDetail->method('getPseudoCardPan')->willReturn(static::PSEUDO_CARD_PAN);

        return $paymentPayoneDetail;
    }
}
