<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\Transfer\DebitResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\PayoneBusinessFactory;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\PayoneDependencyProvider;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManager;
use SprykerEco\Zed\Payone\Persistence\PayonePersistenceFactory;
use SprykerEco\Zed\Payone\Persistence\PayoneRepository;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DebitAdapterMock;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;

class PayoneDebitTest extends AbstractBusinessTest
{
    /**
     * @var int
     */
    public const EXPECTED_TXID = 120;

    /**
     * @var string
     */
    protected const FAKE_PAYMENT_METHOD = 'payment.payone.e_wallet';

    /**
     * @var string
     */
    protected const FAKE_REFERENCE = 'reference';

    /**
     * @var int
     */
    protected const PAYMENT_AMOUT = 10;

    /**
     * @var string
     */
    protected const PAYMENT_CURRENCY = 'EUR';

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
        $this->assertSame(static::EXPECTED_TXID, $debitResponse->getTxid());
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $adapter
     * @param array<string>|null $onlyMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\PayoneBusinessFactory
     */
    public function createBusinessFactoryMock(
        AdapterInterface $adapter,
        ?array $onlyMethods = []
    ): PayoneBusinessFactory {
        $onlyMethods = ['createExecutionAdapter', 'getRepository'];

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
        $businessFactoryMock->method('getRepository')->willReturn($this->createRepositoryMock());

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
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Persistence\PayoneRepository
     */
    protected function createRepositoryMock(): PayoneRepository
    {
        $persistenceFactory = new PayonePersistenceFactory();

        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $this->createPaymentPayone($saveOrderTransfer->getIdSalesOrder());

        $paymentPayoneQuery = $persistenceFactory->createPaymentPayoneQuery()->lastCreatedFirst();

        $queryContainerMock = $this->createPartialMock(
            PayoneRepository::class,
            ['createPaymentById', 'createPaymentByTransactionIdQuery', 'getFactory'],
        );

        $queryContainerMock->method('createPaymentById')->willReturn($paymentPayoneQuery);
        $queryContainerMock->method('createPaymentByTransactionIdQuery')->willReturn($paymentPayoneQuery);
        $queryContainerMock->method('getFactory')->willReturn($persistenceFactory);

        return $queryContainerMock;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    public function createPaymentPayone(int $idSalesOrder): SpyPaymentPayone
    {
        $spyPaymentPayoneDetail = new SpyPaymentPayoneDetail();
        $spyPaymentPayoneDetail->setAmount(static::PAYMENT_AMOUT);
        $spyPaymentPayoneDetail->setCurrency(static::PAYMENT_CURRENCY);

        $paymentPayoneEntity = (new SpyPaymentPayone())
            ->setFkSalesOrder($idSalesOrder)
            ->setTransactionId(1)
            ->setPaymentMethod(static::FAKE_PAYMENT_METHOD)
            ->setReference(static::FAKE_REFERENCE)
            ->setSpyPaymentPayoneDetail($spyPaymentPayoneDetail);
        $paymentPayoneEntity->save();

        return $paymentPayoneEntity;
    }
}
