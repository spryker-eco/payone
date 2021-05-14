<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\DataBuilder\PayoneBankAccountCheckBuilder;
use Generated\Shared\DataBuilder\PayoneCreditCardBuilder;
use Generated\Shared\DataBuilder\PayoneDebitBuilder;
use Generated\Shared\DataBuilder\PayonePaymentBuilder;
use Generated\Shared\Transfer\CreditCardCheckResponseTransfer;
use Generated\Shared\Transfer\DebitResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Shared\Testify\AbstractDataBuilder;
use Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\BankAccountCheckResponseContainer;
use SprykerEco\Zed\Payone\Business\PayoneBusinessFactory;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\PayoneDependencyProvider;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManager;
use SprykerEco\Zed\Payone\Persistence\PayonePersistenceFactory;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainer;
use SprykerEco\Zed\Payone\Persistence\PayoneRepository;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\AbstractAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\BankAccountCheckAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\CreditCardCheckAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DebitAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DummyAdapter;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\GetExpressCheckoutAdapterMock;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class PayoneDebitTest extends AbstractBusinessTest
{
    const EXPECTED_TXID = 'testtxid';

    /**
     * @var \SprykerEcoTest\Zed\Payone\PayoneZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([PayoneZedTester::TEST_STATE_MACHINE_NAME]);

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            'request_stack' => $this->getRequestStackMock(),
        ]));
    }

    /**
     * @return void
     */
    public function testDebit(): void
    {
        //Arrange
        $adapter = new DebitAdapterMock();
        $adapter->setExpectSuccess(true);
        $facadeMock = $this->createFacadeMock($adapter);

        //Act
        $debitResponse = $facadeMock->debitPayment(1);

        //Assert
        $this->assertInstanceOf(DebitResponseTransfer::class, $debitResponse);
        $this->assertSame(self::EXPECTED_TXID, $debitResponse->getTxid());
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
        $onlyMethods = ['createExecutionAdapter', 'getQueryContainer'];

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

        $businessFactoryMock->setRepository(new PayoneRepository());
        $businessFactoryMock->setEntityManager(new PayoneEntityManager());
        $businessFactoryMock->method('getQueryContainer')->willReturn($this->createQueryContainerMock());

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
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Persistence\PayoneQueryContainer
     */
    protected function createQueryContainerMock(): PayoneQueryContainer
    {
        $persistenceFactory = new PayonePersistenceFactory();
        $paymentPayoneQuery = $persistenceFactory->createPaymentPayoneQuery()->lastCreatedFirst();

        $queryContainerMock = $this->createPartialMock(
            PayoneQueryContainer::class,
            ['createPaymentById', 'createPaymentByTransactionIdQuery', 'getFactory']
        );

        $queryContainerMock->method('createPaymentById')->willReturn($paymentPayoneQuery);
        $queryContainerMock->method('createPaymentByTransactionIdQuery')->willReturn($paymentPayoneQuery);
        $queryContainerMock->method('getFactory')->willReturn($persistenceFactory);

        return $queryContainerMock;
    }
}
