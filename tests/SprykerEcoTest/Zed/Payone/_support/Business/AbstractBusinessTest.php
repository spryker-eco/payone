<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\PayoneBusinessFactory;
use SprykerEco\Zed\Payone\Business\PayoneFacade;
use SprykerEco\Zed\Payone\Business\PayoneFacadeInterface;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\PayoneDependencyProvider;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManager;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainer;
use SprykerEco\Zed\Payone\Persistence\PayoneRepository;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Request
 * @group AbstractFacadeTest
 */
abstract class AbstractBusinessTest extends Test
{
    /**
     * @var \Orm\Zed\Payone\Persistence\Base\SpyPaymentPayone
     */
    protected $spyPaymentPayone;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     * @var \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface
     */
    protected $payoneFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setupConfig();
        $this->setUpSalesOrderTestData();
        $this->setupQuoteTransfer();
        $this->orderTransfer = $this->getOrderTransfer();
        $this->orderTransfer
            ->setIdSalesOrder($this->orderEntity->getIdSalesOrder());

        $this->payoneFacade = new PayoneFacade();
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Payone\Business\PayoneBusinessFactory|null $payoneBusinessFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\PayoneFacadeInterface
     */
    public function createFacadeMock(
        AdapterInterface $adapter,
        ?PayoneBusinessFactory $payoneBusinessFactory = null
    ): PayoneFacadeInterface {
        if (!$payoneBusinessFactory) {
            $payoneBusinessFactory = $this->createBusinessFactoryMock($adapter);
        }

        $facade = $this
            ->getMockBuilder(PayoneFacade::class)
            ->onlyMethods(['getFactory'])->getMock();
        $facade->expects($this->any())
            ->method('getFactory')
            ->willReturn($payoneBusinessFactory);

        return $facade;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $adapter
     * @param string[]|null $onlyMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\PayoneBusinessFactory
     */
    public function createBusinessFactoryMock(
        AdapterInterface $adapter,
        ?array $onlyMethods = []
    ): PayoneBusinessFactory {
        $onlyMethods[] = 'createExecutionAdapter';

        /** @var \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\PayoneBusinessFactory $businessFactoryMock */
        $businessFactoryMock = $this
            ->getMockBuilder(PayoneBusinessFactory::class)
            ->onlyMethods($onlyMethods)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $businessFactoryMock->setConfig(new PayoneConfig());
        $businessFactoryMock->setQueryContainer(new PayoneQueryContainer());
        $businessFactoryMock->setRepository(new PayoneRepository());
        $businessFactoryMock->setEntityManager(new PayoneEntityManager());

        $container = new Container();
        $payoneDependencyProvider = new PayoneDependencyProvider();
        $payoneDependencyProvider->provideBusinessLayerDependencies($container);
        $businessFactoryMock->setContainer($container);

        $businessFactoryMock
            ->expects($this->any())
            ->method('createExecutionAdapter')
            ->willReturn($adapter);

        return $businessFactoryMock;
    }

    /**
     * @return void
     */
    protected function setupConfig()
    {
        $this->getConfigHelper()->setConfig(
            PayoneConstants::PAYONE,
            [
                PayoneConstants::PAYONE_CREDENTIALS_ENCODING => 'UTF-8',
                PayoneConstants::PAYONE_CREDENTIALS_KEY => '',
                PayoneConstants::PAYONE_CREDENTIALS_MID => '',
                PayoneConstants::PAYONE_CREDENTIALS_AID => '',
                PayoneConstants::PAYONE_CREDENTIALS_PORTAL_ID => '',
                PayoneConstants::PAYONE_PAYMENT_GATEWAY_URL => 'https://api.pay1.de/post-gateway/',
                PayoneConstants::PAYONE_REDIRECT_SUCCESS_URL => '/checkout/success',
                PayoneConstants::PAYONE_REDIRECT_ERROR_URL => '/checkout/payment',
                PayoneConstants::PAYONE_REDIRECT_BACK_URL => '/payone/regular-redirect-payment-cancellation',
                PayoneConstants::PAYONE_MODE => 'test',
                PayoneConstants::PAYONE_EMPTY_SEQUENCE_NUMBER => 0,
                PayoneConstants::PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL => '',
                PayoneConstants::PAYONE_EXPRESS_CHECKOUT_FAILURE_URL => '',
                PayoneConstants::PAYONE_EXPRESS_CHECKOUT_BACK_URL => '',
                PayoneConstants::PAYONE_ADDRESS_CHECK_TYPE => 'BA',
                PayoneConstants::PAYONE_CONSUMER_SCORE_TYPE => 'IH',
                PayoneConstants::PAYONE_GREEN_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_INVOICE,
                    PayoneConfig::PAYMENT_METHOD_CREDIT_CARD,
                ],
                PayoneConstants::PAYONE_YELLOW_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER,
                ],
                PayoneConstants::PAYONE_RED_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT,
                ],
                PayoneConstants::PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS => [
                    PayoneConfig::PAYMENT_METHOD_CREDIT_CARD,
                    PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER,
                    PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT,
                ],
            ]
        );
    }

    /**
     * @return \Codeception\Module
     */
    protected function getConfigHelper()
    {
        return $this->getModule('\\' . ConfigHelper::class);
    }

    /**
     * @return void
     */
    protected function setUpSalesOrderTestData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');
        $billingAddress = new SpySalesOrderAddress();
        $billingAddress->fromArray($this->getAddressTransfer('billing')->toArray());
        $billingAddress->setFkCountry($country->getIdCountry())->save();

        $shippingAddress = new SpySalesOrderAddress();
        $shippingAddress->fromArray($this->getAddressTransfer('shipping')->toArray());
        $shippingAddress->setFkCountry($country->getIdCountry())->save();

        $customer = (new SpyCustomerQuery())
            ->filterByFirstName('John')
            ->filterByLastName('Doe')
            ->filterByEmail('john@doe.com')
            ->filterByDateOfBirth('1970-01-01')
            ->filterByGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->filterByCustomerReference('payone-pre-authorization-test')
            ->findOneOrCreate();
        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setOrderReference('TEST--1')
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($shippingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer);

        $this->orderEntity->save();

        $stateEntity = $this->createOrderItemStateEntity();
        $processEntity = $this->createOrderProcessEntity();

        $orderItemEntity = (new SpySalesOrderItem())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setFkOmsOrderItemState($stateEntity->getIdOmsOrderItemState())
            ->setFkOmsOrderProcess($processEntity->getIdOmsOrderProcess())
            ->setName('test product')
            ->setSku('1324354657687980')
            ->setGrossPrice(1000)
            ->setQuantity(1);
        $orderItemEntity->save();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer()
    {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference('TEST--1')
            ->setTotals($this->getTotalsTransfer())
            ->setBillingAddress($this->getAddressTransfer('billing'))
            ->setShippingAddress($this->getAddressTransfer('shipping'))
            ->setCustomer($this->getCustomerTransfer())
            ->addItem($this->getItemTransfer(1))
            ->addItem($this->getItemTransfer(2));

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getTotalsTransfer()
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(3346)
            ->setSubtotal(2856)
            ->setDiscountTotal(0)
            ->setExpenseTotal(490);

        return $totalsTransfer;
    }

    /**
     * @param string $itemPrefix
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressTransfer($itemPrefix)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName($itemPrefix . 'John')
            ->setLastName($itemPrefix . 'Doe')
            ->setCity('Berlin')
            ->setIso2Code('DE')
            ->setAddress1($itemPrefix . 'Straße des 17. Juni')
            ->setAddress2($itemPrefix . '135')
            ->setAddress3($itemPrefix . '135')
            ->setZipCode($itemPrefix . '10623')
            ->setSalutation('Mr')
            ->setPhone($itemPrefix . '12345678');

        return $addressTransfer;
    }

    /**
     * @param string $itemPrefix
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemTransfer($itemPrefix)
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setName($itemPrefix . 'test')
            ->setSku($itemPrefix . '33333')
            ->setGroupKey($itemPrefix . '33333333333')
            ->setQuantity((int)$itemPrefix . '2')
            ->setUnitGrossPrice((int)$itemPrefix . '1')
            ->setTaxRate((int)$itemPrefix . '9')
            ->setUnitDiscountAmountFullAggregation((int)$itemPrefix . '9')
            ->setUnitGrossPrice((int)$itemPrefix . '55555');

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('test@test.com')
            ->setFirstName('John')
            ->setPhone('123-123-123')
            ->setCompany('company test')
            ->setCustomerReference('payone-pre-authorization-test')
            ->setDateOfBirth('1991-11-11')
            ->setLastName('Doe');

        return $customerTransfer;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    protected function createOrderItemStateEntity()
    {
        $stateEntity = new SpyOmsOrderItemState();
        $stateEntity->setName('test item state');
        $stateEntity->save();

        return $stateEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function createOrderProcessEntity()
    {
        $processEntity = new SpyOmsOrderProcess();
        $processEntity->setName('test process');
        $processEntity->save();

        return $processEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setupQuoteTransfer()
    {
        $this->quoteTransfer = (new QuoteTransfer())
            ->setTotals($this->getTotalsTransfer())
            ->setBillingAddress($this->getAddressTransfer('billing'))
            ->setShippingAddress($this->getAddressTransfer('shipping'))
            ->setCustomer($this->getCustomerTransfer())
            ->setPayment($this->getPaymentTransfer())
            ->addItem($this->getItemTransfer(1))
            ->addItem($this->getItemTransfer(2));

        return $this->quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPaymentTransfer()
    {
        return (new PaymentTransfer())
            ->setPayone(
                (new PayonePaymentTransfer())
            )
            ->setPaymentProvider(PayoneConfig::PROVIDER_NAME);
    }
}
