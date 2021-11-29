<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
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
    /**
     * @var string
     */
    public const PSEUDO_CARD_PAN = '1234567890123456';

    /**
     * @var string
     */
    public const CARD_TYPE = 'V';

    /**
     * @var string
     */
    public const CARD_EXPIRE_DATE = '1609';

    /**
     * @var string
     */
    public const STANDARD_PARAMETER_CLEARING_TYPE = 'cc';

    /**
     * @var array
     */
    public const AUTHORIZATION_CREDIT_CARD_PSEUDO_REQUIRED_PARAMS = [
        'pseudocardpan' => self::PSEUDO_CARD_PAN,
    ];

    /**
     * @var array
     */
    public const PREAUTHORIZATION_CREDIT_CARD_PSEUDO_REQUIRED_PARAMS = [
        'pseudocardpan' => self::PSEUDO_CARD_PAN,
    ];

    /**
     * @var array
     */
    public const CREDIT_CARD_CHECK_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'cardpan' => self::PSEUDO_CARD_PAN,
        'cardtype' => self::CARD_TYPE,
        'cardexpiredate' => self::CARD_EXPIRE_DATE,
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
    public function testMapPaymentToAuthorization(): void
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
    public function testMapPaymentToCapture(): void
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
    public function testMapPaymentToRefund(): void
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
    public function testMapPaymentToDebit(): void
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
    public function testMapCreditCardCheck(): void
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
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPaymentPayoneDetailMock(): SpyPaymentPayoneDetail
    {
        $paymentPayoneDetail = parent::getPaymentPayoneDetailMock();
        $paymentPayoneDetail->method('getPseudoCardPan')->willReturn(static::PSEUDO_CARD_PAN);

        return $paymentPayoneDetail;
    }
}
