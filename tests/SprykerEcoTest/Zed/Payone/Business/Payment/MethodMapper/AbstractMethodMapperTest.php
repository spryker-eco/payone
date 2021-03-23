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
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;

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
    public const STANDARD_PARAMETER_AID = '12345';
    public const STANDARD_PARAMETER_CURRENCY = 'EUR';
    public const ADDRESS_FIRST_NAME = 'Max';
    public const ADDRESS_LAST_NAME = 'Mustermann';
    public const COUNTRY_ISO2CODE = 'de';
    public const TRANSACTION_ID = '1234567890';
    public const AMOUNT_FULL = 100;
    public const PAYMENT_REFERENCE = 'TX1234567890abcd';
    public const DEFAULT_SEQUENCE_NUMBER = 0;
    public const DEFAULT_EMAIL = 'default@email.com';
    public const STANDARD_PARAMETER_LANGUAGE = 'en';

    public const PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
    ];

    public const AUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
    ];

    public const CAPTURE_COMMON_REQUIRED_PARAMS = [
        'txid' => self::TRANSACTION_ID,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    public const REFUND_COMMON_REQUIRED_PARAMS = [
        'txid' => self::TRANSACTION_ID,
        'sequencenumber' => self::DEFAULT_SEQUENCE_NUMBER,
        // Amount is added outside of the mapper
        //'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

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
    public function preparePaymentMethodMapper($paymentMethodMapper)
    {
        $paymentMethodMapper->setStandardParameter($this->getStandardParameterMock());
        $paymentMethodMapper->setSequenceNumberProvider($this->getSequenceNumberProviderMock());
        $paymentMethodMapper->setUrlHmacGenerator($this->getUrlHmacGeneratorMock());

        return $paymentMethodMapper;
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function getPaymentEntityMock()
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
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail
     */
    protected function getPaymentPayoneDetailMock()
    {
        $paymentPayoneDetail = $this->getMockBuilder(SpyPaymentPayoneDetail::class)
            ->disableOriginalConstructor()
            ->getMock();
        $paymentPayoneDetail->method('getAmount')->willReturn(static::AMOUNT_FULL);

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
        $salesOrder->method('getOrderTotals')->willReturn($this->getTotals());

        return $salesOrder;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getSalesOrderTransfer()
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
    protected function getStandardParameterMock()
    {
        $standardParameter = $this->getMockBuilder(PayoneStandardParameterTransfer::class)->getMock();
        $standardParameter->method('getAid')->willReturn(static::STANDARD_PARAMETER_AID);
        $standardParameter->method('getCurrency')->willReturn(static::STANDARD_PARAMETER_CURRENCY);
        $standardParameter->method('getLanguage')->willReturn(static::STANDARD_PARAMETER_LANGUAGE);

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
    protected function getTotals(): SpySalesOrderTotals
    {
        $totals = $this->getMockBuilder(SpySalesOrderTotals::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $totals->method('get')->willReturn((new TotalsTransfer())->setSubtotal(100));

        return $totals;
    }
}
