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
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CashOnDelivery;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\EWallet;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\GenericPayment;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Klarna;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Prepayment;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\SecurityInvoice;
use SprykerEco\Zed\Payone\Business\Payment\PaymentManager;
use SprykerEco\Zed\Payone\Business\Payment\PaymentManagerInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilter;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilterInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
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
            $this->createSequenceNumberProvider(),
            $this->createModeDetector(),
            $this->createUrlHmacGenerator(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createOrderPriceDistributor()
        );

        foreach ($this->getAvailablePaymentMethods() as $paymentMethod) {
            $paymentManager->registerPaymentMethodMapper($paymentMethod);
        }

        return $paymentManager;
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
    protected function createExecutionAdapter()
    {
        return new Guzzle(
            $this->getStandardParameter()->getPaymentGatewayUrl(),
            $this->createApiCallLogWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriter
     */
    protected function createApiCallLogWriter()
    {
        return new ApiCallLogWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    protected function createSequenceNumberProvider()
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
    protected function createKeyHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HashGenerator
     */
    protected function createKeyHashGenerator()
    {
        return new HashGenerator(
            $this->createHashProvider()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface
     */
    protected function createUrlHmacGenerator()
    {
        return new UrlHmacGenerator();
    }

    /**
     * @return \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected function createModeDetector()
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
    protected function getAvailablePaymentMethods()
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
    protected function getStandardParameter()
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HashProvider
     */
    protected function createHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo
     */
    protected function createCreditCardPseudo($storeConfig)
    {
        $creditCardPseudo = new CreditCardPseudo($storeConfig);

        return $creditCardPseudo;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit
     */
    protected function createDirectDebit($storeConfig)
    {
        $creditCardPseudo = new DirectDebit($storeConfig);

        return $creditCardPseudo;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice
     */
    protected function createInvoice($storeConfig)
    {
        $invoice = new Invoice($storeConfig);

        return $invoice;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    protected function createSecurityInvoice($storeConfig): PaymentMethodMapperInterface
    {
        $invoice = new SecurityInvoice($storeConfig, $this->getConfig());

        return $invoice;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer
     */
    protected function createOnlineBankTransfer($storeConfig)
    {
        $onlineBankTransfer = new OnlineBankTransfer($storeConfig);

        return $onlineBankTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\EWallet
     */
    protected function createEWallet($storeConfig)
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
    protected function createCashOnDelivery(Store $storeConfig, PayoneToGlossaryFacadeInterface $glossary): PaymentMethodMapperInterface
    {
        return new CashOnDelivery($storeConfig, $glossary);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Prepayment
     */
    protected function createPrepayment($storeConfig)
    {
        $prepayment = new Prepayment($storeConfig);

        return $prepayment;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\GenericPayment
     */
    protected function createGenericPayment(Store $store)
    {
        $genericPayment = new GenericPayment($store);

        return $genericPayment;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Klarna
     */
    protected function createKlarna(Store $store)
    {
        $klarna = new Klarna($store);

        return $klarna;
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
    protected function createRiskCheckMapper(): RiskCheckMapperInterface
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
    protected function createRiskCheckFactory(): RiskCheckFactoryInterface
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
}
