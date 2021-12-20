<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Payment\MethodMapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use PHPUnit_Framework_TestCase;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\AbstractMapper;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group AbstractMethodMapperTest
 */
class AbstractMethodMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    public const STANDARD_PARAMETER_AID = '12345';

    /**
     * @var string
     */
    public const STANDARD_PARAMETER_KEY = 'key';

    /**
     * @var string
     */
    public const STANDARD_PARAMETER_CURRENCY = 'EUR';

    /**
     * @var string
     */
    public const ADDRESS_FIRST_NAME = 'Max';

    /**
     * @var string
     */
    public const ADDRESS_LAST_NAME = 'Mustermann';

    /**
     * @var string
     */
    public const COUNTRY_ISO2CODE = 'de';

    /**
     * @var string
     */
    public const TRANSACTION_ID = '1234567890';

    /**
     * @var int
     */
    public const AMOUNT_FULL = 100;

    /**
     * @var string
     */
    public const PAYMENT_REFERENCE = 'TX1234567890abcd';

    /**
     * @var int
     */
    public const DEFAULT_SEQUENCE_NUMBER = 0;

    /**
     * @var string
     */
    public const DEFAULT_EMAIL = 'default@email.com';

    /**
     * @var string
     */
    public const STANDARD_PARAMETER_LANGUAGE = 'en';

    /**
     * @var string
     */
    public const DEFAULT_CITY = 'Berlin';

    /**
     * @var string
     */
    public const DEFAULT_SALUTATION = 'Mr';

    /**
     * @var string
     */
    public const DEFAULT_ADDRESS_3 = 'Address 3';

    /**
     * @var string
     */
    public const DEFAULT_ZIP = '123456';

    /**
     * @var string
     */
    public const DEFAULT_PHONE = '777-555-333';

    /**
     * @var string
     */
    public const DEFAULT_PERSONAL_ID = '3lkn534';

    /**
     * @var string
     */
    public const DEFAULT_ORDER_REFERENCE_ID = '456j4nk56';

    /**
     * @var string
     */
    protected const CLIENT_IP = '127.0.0.1';

    /**
     * @var array
     */
    public const PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
    ];

    /**
     * @var array
     */
    public const AUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
    ];

    /**
     * @var array
     */
    public const CAPTURE_COMMON_REQUIRED_PARAMS = [
        'txid' => self::TRANSACTION_ID,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @var array
     */
    public const REFUND_COMMON_REQUIRED_PARAMS = [
        'txid' => self::TRANSACTION_ID,
        'sequencenumber' => self::DEFAULT_SEQUENCE_NUMBER,
        // Amount is added outside of the mapper
        //'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @var array
     */
    public const DEBIT_COMMON_REQUIRED_PARAMS = [
        'txid' => self::TRANSACTION_ID,
        'sequencenumber' => self::DEFAULT_SEQUENCE_NUMBER,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\AbstractMapper $paymentMethodMapper
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\AbstractMapper
     */
    public function preparePaymentMethodMapper(
        AbstractMapper $paymentMethodMapper
    ): AbstractMapper {
        $paymentMethodMapper->setStandardParameter($this->getStandardParameterMock());
        $paymentMethodMapper->setSequenceNumberProvider($this->getSequenceNumberProviderMock());
        $paymentMethodMapper->setUrlHmacGenerator($this->getUrlHmacGeneratorMock());

        return $paymentMethodMapper;
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function getPaymentEntityMock(): SpyPaymentPayone
    {
        $paymentEntity = $this->getMockBuilder(SpyPaymentPayone::class)
            ->disableOriginalConstructor()
            ->getMock();

        $paymentPayoneDetail = $this->getPaymentPayoneDetailMock();
        $salesOrder = $this->getSalesOrderMock();

        $paymentEntity->method('getSpyPaymentPayoneDetail')->willReturn($paymentPayoneDetail);
        $paymentEntity->method('getSpySalesOrder')->willReturn($salesOrder);
        $paymentEntity->method('getTransactionId')->willReturn(static::TRANSACTION_ID);
        $paymentEntity->method('getReference')->willReturn(static::PAYMENT_REFERENCE);

        return $paymentEntity;
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPaymentPayoneDetailMock(): SpyPaymentPayoneDetail
    {
        $paymentPayoneDetail = $this->getMockBuilder(SpyPaymentPayoneDetail::class)
            ->disableOriginalConstructor()
            ->getMock();
        $paymentPayoneDetail->method('getAmount')->willReturn(static::AMOUNT_FULL);
        $paymentPayoneDetail->method('getBankCountry')->willReturn('Germany');
        $paymentPayoneDetail->method('getBankAccount')->willReturn('BankAccount');
        $paymentPayoneDetail->method('getBankCode')->willReturn('BankCode');
        $paymentPayoneDetail->method('getBankGroupType')->willReturn('BankGroupType');
        $paymentPayoneDetail->method('getIban')->willReturn('Iban');
        $paymentPayoneDetail->method('getBic')->willReturn('Bic');
        $paymentPayoneDetail->method('getShippingProvider')->willReturn('ShippingProvider');

        return $paymentPayoneDetail;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getSalesOrderMock(): SpySalesOrder
    {
        $salesOrder = $this->getMockBuilder(SpySalesOrder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $salesOrder->method('getBillingAddress')->willReturn($this->getAddressMock());
        $salesOrder->method('getShippingAddress')->willReturn($this->getAddressMock());
        $salesOrder->method('getEmail')->willReturn(static::DEFAULT_EMAIL);
        $salesOrder->method('getOrderTotals')->willReturn($this->getSalesOrderTotals());
        $salesOrder->method('getCustomerReference')->willReturn(static::DEFAULT_PERSONAL_ID);
        $salesOrder->method('getOrderReference')->willReturn(static::DEFAULT_ORDER_REFERENCE_ID);

        return $salesOrder;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getSalesOrderTransfer(): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();

        $item = new ItemTransfer();
        $orderTransfer->setItems(new ArrayObject([$item]));

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(100);
        $totalsTransfer->getSubtotal(100);

        $orderTransfer->setTotals($totalsTransfer);

        return $orderTransfer;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function getAddressMock(): SpySalesOrderAddress
    {
        $address = $this->getMockBuilder(SpySalesOrderAddress::class)
            ->disableOriginalConstructor()
            ->getMock();
        $address->method('getCountry')->willReturn($this->getCountryMock());
        $address->method('getFirstName')->willReturn(static::ADDRESS_FIRST_NAME);
        $address->method('getLastName')->willReturn(static::ADDRESS_LAST_NAME);
        $address->method('getEmail')->willReturn(static::DEFAULT_EMAIL);
        $address->method('getCity')->willReturn(static::DEFAULT_CITY);
        $address->method('getSalutation')->willReturn(static::DEFAULT_SALUTATION);
        $address->method('getAddress3')->willReturn(static::DEFAULT_ADDRESS_3);
        $address->method('getZipCode')->willReturn(static::DEFAULT_ZIP);
        $address->method('getPhone')->willReturn(static::DEFAULT_PHONE);
        $address->method('getCompany')->willReturn('Company');

        return $address;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Country\Persistence\SpyCountry
     */
    protected function getCountryMock(): SpyCountry
    {
        $country = $this->getMockBuilder(SpyCountry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $country->method('getIso2Code')->willReturn(static::COUNTRY_ISO2CODE);

        return $country;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Store
     */
    protected function getStoreConfigMock(): Store
    {
        $storeConfig = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeConfig->method('getCurrentCountry')->willReturn(static::COUNTRY_ISO2CODE);

        return $storeConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function getStandardParameterMock(): PayoneStandardParameterTransfer
    {
        $standardParameter = $this->getMockBuilder(PayoneStandardParameterTransfer::class)->getMock();
        $standardParameter->method('getAid')->willReturn(static::STANDARD_PARAMETER_AID);
        $standardParameter->method('getCurrency')->willReturn(static::STANDARD_PARAMETER_CURRENCY);
        $standardParameter->method('getLanguage')->willReturn(static::STANDARD_PARAMETER_LANGUAGE);
        $standardParameter->method('getKey')->willReturn(static::STANDARD_PARAMETER_KEY);

        return $standardParameter;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider
     */
    protected function getSequenceNumberProviderMock(): SequenceNumberProvider
    {
        $sequenceNumberProvider = $this->getMockBuilder(SequenceNumberProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sequenceNumberProvider->method('getNextSequenceNumber')->willReturn(static::DEFAULT_SEQUENCE_NUMBER);

        return $sequenceNumberProvider;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator
     */
    protected function getUrlHmacGeneratorMock(): UrlHmacGenerator
    {
        $urlHmacGenerator = $this->getMockBuilder(UrlHmacGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $urlHmacGenerator;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderTotals
     */
    protected function getSalesOrderTotals(): SpySalesOrderTotals
    {
        $orderTotals = $this->getMockBuilder(SpySalesOrderTotals::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $orderTotals->method('get')->willReturn($this->getTotals());

        return $orderTotals;
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getTotals(): TotalsTransfer
    {
        $totals = new TotalsTransfer();

        $totals->setSubtotal(100);
        $totals->setGrandTotal(100);

        return $totals;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStackMock(): RequestStack
    {
        $mock = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentRequest'])
            ->getMock();

        $mock->method('getCurrentRequest')->willReturn($this->getCurrentRequestMock());

        return $mock;
    }
}
