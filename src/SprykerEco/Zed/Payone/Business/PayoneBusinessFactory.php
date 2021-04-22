<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business;

use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\Http\Guzzle;
use SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriter;
use SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerEco\Zed\Payone\Business\ApiLog\ApiLogFinder;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributor;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Key\HashProvider;
use SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator;
use SprykerEco\Zed\Payone\Business\Mode\ModeDetector;
use SprykerEco\Zed\Payone\Business\Order\OrderManager;
use SprykerEco\Zed\Payone\Business\Order\OrderManagerInterface;
use SprykerEco\Zed\Payone\Business\Order\PayoneOrderItemStatusFinder;
use SprykerEco\Zed\Payone\Business\Order\PayoneOrderItemStatusFinderInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\OrderItemsMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CashOnDelivery;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\EWallet;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\GenericPayment;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapper;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Prepayment;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\SecurityInvoice;
use SprykerEco\Zed\Payone\Business\Payment\PaymentManager;
use SprykerEco\Zed\Payone\Business\Payment\PaymentManagerInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilter;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilterInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PayoneKlarnaStartSessionHandler;
use SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactory;
use SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapper;
use SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapperInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\RiskCheckManager;
use SprykerEco\Zed\Payone\Business\RiskManager\RiskCheckManagerInterface;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;
use SprykerEco\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface;
use SprykerEco\Zed\Payone\PayoneDependencyProvider;

/**
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface getEntityManager()
 */
class PayoneBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    private $standardParameter;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentManagerInterface
     */
    public function createPaymentManager(): PaymentManagerInterface
    {
        $paymentManager = new PaymentManager(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createKeyHashGenerator(),
            $this->createModeDetector(),
            $this->createUrlHmacGenerator(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createOrderPriceDistributor(),
            $this->createStandartParameterMapper(),
            $this->createOrderItemsMapper(),
            $this->createShipmentMapper(),
            $this->createDiscountMapper(),
            $this->createPaymentMapperManager(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\PayoneKlarnaStartSessionHandler
     */
    public function createPayoneKlarnaStartSessionHandler()
    {
        return new PayoneKlarnaStartSessionHandler(
            $this->createExecutionAdapter(),
            $this->createKeyHashGenerator(),
            $this->createUrlHmacGenerator(),
            $this->createStandartParameterMapper(),
            $this->createOrderItemsMapper(),
            $this->createShipmentMapper(),
            $this->createDiscountMapper(),
            $this->createPaymentMapperManager(),
            $this->getStandardParameter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Order\OrderManagerInterface
     */
    public function createOrderManager(): OrderManagerInterface
    {
        return new OrderManager($this->getConfig(), $this->getEntityManager());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManagerInterface
     */
    public function createTransactionStatusManager()
    {
        return new TransactionStatusUpdateManager(
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createKeyHashGenerator()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\ApiLog\ApiLogFinderInterface
     */
    public function createApiLogFinder()
    {
        return new ApiLogFinder(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    public function createExecutionAdapter()
    {
        return new Guzzle(
            $this->getStandardParameter()->getPaymentGatewayUrl(),
            $this->createApiCallLogWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriter
     */
    public function createApiCallLogWriter()
    {
        return new ApiCallLogWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    public function createSequenceNumberProvider()
    {
        $defaultEmptySequenceNumber = $this->getConfig()->getEmptySequenceNumber();

        return new SequenceNumberProvider(
            $this->getQueryContainer(),
            $defaultEmptySequenceNumber
        );
    }

    /**
     * @return \SprykerEco\Shared\Payone\Dependency\HashInterface
     */
    public function createKeyHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HashGenerator
     */
    public function createKeyHashGenerator()
    {
        return new HashGenerator(
            $this->createHashProvider()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface
     */
    public function createUrlHmacGenerator()
    {
        return new UrlHmacGenerator();
    }

    /**
     * @return \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    public function createModeDetector()
    {
        $modeDetector = new ModeDetector($this->getConfig());

        return $modeDetector;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest
     */
    public function createTransactionStatusUpdateRequest(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer)
    {
        return new TransactionStatusRequest($transactionStatusUpdateTransfer->toArray());
    }

    /**
     * @return array
     */
    public function getAvailablePaymentMethods()
    {
        $storeConfig = $this->getProvidedDependency(PayoneDependencyProvider::STORE_CONFIG);

        return [
            PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO => $this->createCreditCardPseudo($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_INVOICE => $this->createInvoice($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_SECURITY_INVOICE => $this->createSecurityInvoice($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER => $this->createOnlineBankTransfer($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_E_WALLET => $this->createEWallet($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT => $this->createPrepayment($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_DIRECT_DEBIT => $this->createDirectDebit($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT => $this->createGenericPayment($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_CASH_ON_DELIVERY => $this->createCashOnDelivery($storeConfig, $this->getGlossaryFacade()),
            PayoneApiConstants::PAYMENT_METHOD_KLARNA => $this->createKlarna($storeConfig),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    public function getStandardParameter()
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HashProvider
     */
    public function createHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo
     */
    public function createCreditCardPseudo($storeConfig)
    {
        $creditCardPseudo = new CreditCardPseudo($storeConfig);

        return $creditCardPseudo;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit
     */
    public function createDirectDebit($storeConfig)
    {
        $creditCardPseudo = new DirectDebit($storeConfig);

        return $creditCardPseudo;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice
     */
    public function createInvoice($storeConfig)
    {
        $invoice = new Invoice($storeConfig);

        return $invoice;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    public function createSecurityInvoice($storeConfig): PaymentMethodMapperInterface
    {
        $invoice = new SecurityInvoice($storeConfig, $this->getConfig());

        return $invoice;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer
     */
    public function createOnlineBankTransfer($storeConfig)
    {
        $onlineBankTransfer = new OnlineBankTransfer($storeConfig);

        return $onlineBankTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\EWallet
     */
    public function createEWallet($storeConfig)
    {
        $EWallet = new EWallet($storeConfig);

        return $EWallet;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     * @param \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface $glossary
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    public function createCashOnDelivery(Store $storeConfig, PayoneToGlossaryFacadeInterface $glossary): PaymentMethodMapperInterface
    {
        return new CashOnDelivery($storeConfig, $glossary);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Prepayment
     */
    public function createPrepayment($storeConfig)
    {
        $prepayment = new Prepayment($storeConfig);

        return $prepayment;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\GenericPayment
     */
    public function createGenericPayment(Store $store)
    {
        $genericPayment = new GenericPayment($store);

        return $genericPayment;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapperInterface
     */
    public function createKlarna(Store $store): KlarnaPaymentMapperInterface
    {
        return new KlarnaPaymentMapper($store, $this->getRequestStack());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\RiskManager\RiskCheckManagerInterface
     */
    public function createRiskCheckManager(): RiskCheckManagerInterface
    {
        return new RiskCheckManager(
            $this->createRiskCheckMapper(),
            $this->createExecutionAdapter(),
            $this->createRiskCheckFactory()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapperInterface
     */
    public function createRiskCheckMapper(): RiskCheckMapperInterface
    {
        return new RiskCheckMapper(
            $this->createRiskCheckFactory(),
            $this->getStandardParameter(),
            $this->createModeDetector(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface
     */
    public function createRiskCheckFactory(): RiskCheckFactoryInterface
    {
        return new RiskCheckFactory();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilterInterface
     */
    public function createPaymentMethodFilter(): PaymentMethodFilterInterface
    {
        return new PaymentMethodFilter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Order\PayoneOrderItemStatusFinderInterface
     */
    public function createPayoneOrderItemStatusFinder(): PayoneOrderItemStatusFinderInterface
    {
        return new PayoneOrderItemStatusFinder($this->getRepository());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface
     */
    public function createOrderPriceDistributor(): OrderPriceDistributorInterface
    {
        return new OrderPriceDistributor();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper
     */
    public function createStandartParameterMapper(): StandartParameterMapper
    {
        return new StandartParameterMapper($this->createKeyHashGenerator(), $this->createModeDetector());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\OrderItemsMapper
     */
    public function createOrderItemsMapper(): OrderItemsMapper
    {
        return new OrderItemsMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapper
     */
    public function createShipmentMapper(): ShipmentMapper
    {
        return new ShipmentMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager
     */
    public function createPaymentMapperManager(): PaymentMapperManager
    {
        $paymentMapperManager = new PaymentMapperManager(
            $this->createSequenceNumberProvider(),
            $this->createUrlHmacGenerator()
        );

        foreach ($this->getAvailablePaymentMethods() as $paymentMethod) {
            $paymentMapperManager->registerPaymentMethodMapper($paymentMethod, $this->standardParameter);
        }

        return $paymentMapperManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapper
     */
    public function createDiscountMapper(): DiscountMapper
    {
        return new DiscountMapper();
    }

    /**
     * @return mixed
     */
    public function getRequestStack()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
