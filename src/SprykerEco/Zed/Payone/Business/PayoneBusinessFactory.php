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
use SprykerEco\Zed\Payone\Business\ConditionChecker\IsPaymentDataRequiredChecker;
use SprykerEco\Zed\Payone\Business\ConditionChecker\IsPaymentDataRequiredCheckerInterface;
use SprykerEco\Zed\Payone\Business\ConditionChecker\IsRefundPossibleChecker;
use SprykerEco\Zed\Payone\Business\ConditionChecker\IsRefundPossibleCheckerInterface;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributor;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Key\HashProvider;
use SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface;
use SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator;
use SprykerEco\Zed\Payone\Business\Mode\ModeDetector;
use SprykerEco\Zed\Payone\Business\Order\OrderManager;
use SprykerEco\Zed\Payone\Business\Order\OrderManagerInterface;
use SprykerEco\Zed\Payone\Business\Order\PayoneOrderItemStatusFinder;
use SprykerEco\Zed\Payone\Business\Order\PayoneOrderItemStatusFinderInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\OrderHandlingMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\OrderHandlingMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\Hook\CheckoutPostSaveHookExecutor;
use SprykerEco\Zed\Payone\Business\Payment\Hook\CheckoutPostSaveHookExecutorInterface;
use SprykerEco\Zed\Payone\Business\Payment\Hook\PostSaveHook;
use SprykerEco\Zed\Payone\Business\Payment\Hook\PostSaveHookInterface;
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
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneAuthorizeMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneAuthorizeMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBankAccountCheckMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBankAccountCheckMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCaptureMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCaptureMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCreditCardCheckMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCreditCardCheckMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneDebitMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneDebitMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetFileMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetFileMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetInvoiceMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetInvoiceMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetPaypalExpressCheckoutDetailsMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetPaypalExpressCheckoutDetailsMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetSecurityInvoiceMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetSecurityInvoiceMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneInitPaypalExpressCheckoutMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneInitPaypalExpressCheckoutMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneKlarnaStartSessionMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneKlarnaStartSessionMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneManageMandateMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneManageMandateMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialCaptureMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialCaptureMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialRefundMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialRefundMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePreAuthorizeMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePreAuthorizeMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneRefundMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneRefundMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentDetail;
use SprykerEco\Zed\Payone\Business\Payment\PaymentDetailInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilter;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilterInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PayoneLogsReceiver;
use SprykerEco\Zed\Payone\Business\Payment\PayoneLogsReceiverInterface;
use SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReader;
use SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface;
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
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePreAuthorizeMethodSenderInterface
     */
    public function createPreAuthorizeMethodSender(): PayonePreAuthorizeMethodSenderInterface
    {
        $paymentManager = new PayonePreAuthorizeMethodSender(
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->createPayoneBaseAuthorizeSender(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneAuthorizeMethodSenderInterface
     */
    public function createAuthorizeMethodSender(): PayoneAuthorizeMethodSenderInterface
    {
        $paymentManager = new PayoneAuthorizeMethodSender(
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->createPayoneRequestProductDataMapper(),
            $this->createPayoneBaseAuthorizeSender(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCaptureMethodSenderInterface
     */
    public function createCaptureMethodSender(): PayoneCaptureMethodSenderInterface
    {
        $paymentManager = new PayoneCaptureMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->createOrderPriceDistributor(),
            $this->createStandartParameterMapper(),
            $this->createPayoneRequestProductDataMapper(),
            $this->createOrderHandlingMapper()
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialCaptureMethodSenderInterface
     */
    public function createPartialCaptureMethodSender(): PayonePartialCaptureMethodSenderInterface
    {
        $paymentManager = new PayonePartialCaptureMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->createOrderHandlingMapper(),
            $this->getStandardParameter(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createOrderPriceDistributor(),
            $this->createStandartParameterMapper(),
            $this->createShipmentMapper(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneDebitMethodSenderInterface
     */
    public function createDebitMethodSender(): PayoneDebitMethodSenderInterface
    {
        $paymentManager = new PayoneDebitMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneRefundMethodSenderInterface
     */
    public function createRefundMethodSender(): PayoneRefundMethodSenderInterface
    {
        $paymentManager = new PayoneRefundMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->createOrderPriceDistributor(),
            $this->createStandartParameterMapper(),
            $this->createPayoneRequestProductDataMapper(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialRefundMethodSenderInterface
     */
    public function createPartialRefundMethodSender(): PayonePartialRefundMethodSenderInterface
    {
        $paymentManager = new PayonePartialRefundMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createStandartParameterMapper(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBankAccountCheckMethodSenderInterface
     */
    public function createBankAccountCheckMethodSender(): PayoneBankAccountCheckMethodSenderInterface
    {
        $paymentManager = new PayoneBankAccountCheckMethodSender(
            $this->createExecutionAdapter(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCreditCardCheckMethodSenderInterface
     */
    public function createCreditCardCheckMethodSender(): PayoneCreditCardCheckMethodSenderInterface
    {
        $paymentManager = new PayoneCreditCardCheckMethodSender(
            $this->createExecutionAdapter(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneManageMandateMethodSenderInterface
     */
    public function createManageMandateMethodSender(): PayoneManageMandateMethodSenderInterface
    {
        $paymentManager = new PayoneManageMandateMethodSender(
            $this->createExecutionAdapter(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetFileMethodSenderInterface
     */
    public function createGetFileMethodSender(): PayoneGetFileMethodSenderInterface
    {
        $paymentManager = new PayoneGetFileMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetInvoiceMethodSenderInterface
     */
    public function createGetInvoiceMethodSender(): PayoneGetInvoiceMethodSenderInterface
    {
        $paymentManager = new PayoneGetInvoiceMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetSecurityInvoiceMethodSenderInterface
     */
    public function createGetSecurityInvoiceMethodSender(): PayoneGetSecurityInvoiceMethodSenderInterface
    {
        $paymentManager = new PayoneGetSecurityInvoiceMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\PayoneLogsReceiverInterface
     */
    public function createLogsReceiver(): PayoneLogsReceiverInterface
    {
        $paymentManager = new PayoneLogsReceiver(
            $this->getQueryContainer(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneInitPaypalExpressCheckoutMethodSenderInterface
     */
    public function createInitPaypalExpressCheckoutMethodSender(): PayoneInitPaypalExpressCheckoutMethodSenderInterface
    {
        $paymentManager = new PayoneInitPaypalExpressCheckoutMethodSender(
            $this->createPaymentMapperReader(),
            $this->createGenericRequestMethodSender(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGetPaypalExpressCheckoutDetailsMethodSenderInterface
     */
    public function createGetPaypalExpressCheckoutDetailsMethodSender(): PayoneGetPaypalExpressCheckoutDetailsMethodSenderInterface
    {
        $paymentManager = new PayoneGetPaypalExpressCheckoutDetailsMethodSender(
            $this->createPaymentMapperReader(),
            $this->createGenericrequestMethodSender()
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface
     */
    public function createGenericRequestMethodSender(): PayoneGenericRequestMethodSenderInterface
    {
        $paymentManager = new PayoneGenericRequestMethodSender(
            $this->createExecutionAdapter(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
        );

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneKlarnaStartSessionMethodSenderInterface
     */
    public function createPayoneKlarnaStartSessionMethodSender(): PayoneKlarnaStartSessionMethodSenderInterface
    {
        return new PayoneKlarnaStartSessionMethodSender(
            $this->createExecutionAdapter(),
            $this->createStandartParameterMapper(),
            $this->createPayoneRequestProductDataMapper(),
            $this->createKlarnaPaymentMapper($this->getStore()),
            $this->getStandardParameter(),
            $this->createSequenceNumberProvider(),
            $this->createUrlHmacGenerator(),
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
    public function createUrlHmacGenerator(): HmacGeneratorInterface
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
            PayoneApiConstants::PAYMENT_METHOD_KLARNA => $this->createKlarnaPaymentMapper($storeConfig),
        ];
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::STORE_CONFIG);
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
    public function createKlarnaPaymentMapper(Store $store): KlarnaPaymentMapperInterface
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
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentDetailInterface
     */
    public function createPaymentDetail(): PaymentDetailInterface
    {
        return new PaymentDetail($this->getQueryContainer());
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
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    public function createStandartParameterMapper(): StandartParameterMapperInterface
    {
        return new StandartParameterMapper($this->createKeyHashGenerator(), $this->createModeDetector());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface
     */
    public function createPayoneRequestProductDataMapper(): PayoneRequestProductDataMapperInterface
    {
        return new PayoneRequestProductDataMapper(
            $this->createProductsMapper(),
            $this->createShipmentMapper(),
            $this->createDiscountMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapperInterface
     */
    public function createProductsMapper(): ProductMapperInterface
    {
        return new ProductMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface
     */
    public function createShipmentMapper(): ShipmentMapperInterface
    {
        return new ShipmentMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader
     */
    public function createPaymentMapperReader(): PaymentMapperReader
    {
        $paymentMapperReader = new PaymentMapperReader(
            $this->createSequenceNumberProvider(),
            $this->createUrlHmacGenerator()
        );

        foreach ($this->getAvailablePaymentMethods() as $paymentMethod) {
            $paymentMapperReader->registerPaymentMethodMapper($paymentMethod, $this->getStandardParameter());
        }

        return $paymentMapperReader;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface
     */
    public function createDiscountMapper(): DiscountMapperInterface
    {
        return new DiscountMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\OrderHandlingMapperInterface
     */
    public function createOrderHandlingMapper(): OrderHandlingMapperInterface
    {
        return new OrderHandlingMapper();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Reader\PayonePaymentReaderInterface
     */
    public function createPayonePaymentReader(): PayonePaymentReaderInterface
    {
        return new PayonePaymentReader($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\ConditionChecker\IsRefundPossibleCheckerInterface
     */
    public function createIsRefundPossibleChecker(): IsRefundPossibleCheckerInterface
    {
        return new IsRefundPossibleChecker($this->createPayonePaymentReader(), $this->createIsPaymentDataRequiredChecker());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\ConditionChecker\IsPaymentDataRequiredCheckerInterface
     */
    public function createIsPaymentDataRequiredChecker(): IsPaymentDataRequiredCheckerInterface
    {
        return new IsPaymentDataRequiredChecker($this->createPayonePaymentReader());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Hook\PostSaveHookInterface
     */
    public function createPostSaveHook(): PostSaveHookInterface
    {
        return new PostSaveHook($this->getQueryContainer());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Hook\CheckoutPostSaveHookExecutorInterface
     */
    public function createCheckoutPostSaveHookExecutor(): CheckoutPostSaveHookExecutorInterface
    {
        return new CheckoutPostSaveHookExecutor(
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->createPayoneRequestProductDataMapper(),
            $this->createPayoneBaseAuthorizeSender(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface
     */
    public function createPayoneBaseAuthorizeSender(): PayoneBaseAuthorizeSenderInterface
    {
        return new PayoneBaseAuthorizeSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
        );
    }
}
