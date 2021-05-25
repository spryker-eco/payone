<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\Payone\Dependency\HashInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Adapter\Http\Guzzle;
use SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriter;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapper;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapper;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CreditCardCheckResponseMapper;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CreditCardCheckResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\DebitResponseMapper;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\DebitResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\RefundResponseMapper;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\RefundResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerEco\Zed\Payone\Business\ApiLog\ApiLogFinder;
use SprykerEco\Zed\Payone\Business\ApiLog\ApiLogFinderInterface;
use SprykerEco\Zed\Payone\Business\ConditionChecker\PaymentDataChecker;
use SprykerEco\Zed\Payone\Business\ConditionChecker\PaymentDataCheckerInterface;
use SprykerEco\Zed\Payone\Business\ConditionChecker\RefundChecker;
use SprykerEco\Zed\Payone\Business\ConditionChecker\RefundCheckerInterface;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributor;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashProvider;
use SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface;
use SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator;
use SprykerEco\Zed\Payone\Business\Mode\ModeDetector;
use SprykerEco\Zed\Payone\Business\Order\OrderManager;
use SprykerEco\Zed\Payone\Business\Order\OrderManagerInterface;
use SprykerEco\Zed\Payone\Business\Order\PayoneOrderItemStatusFinder;
use SprykerEco\Zed\Payone\Business\Order\PayoneOrderItemStatusFinderInterface;
use SprykerEco\Zed\Payone\Business\Payment\Checker\PayoneBankAccountChecker;
use SprykerEco\Zed\Payone\Business\Payment\Checker\PayoneBankAccountCheckerInterface;
use SprykerEco\Zed\Payone\Business\Payment\Checker\PayoneCreditCardChecker;
use SprykerEco\Zed\Payone\Business\Payment\Checker\PayoneCreditCardCheckerInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\GenericPaymentMethodMapperInterface;
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
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCaptureMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCaptureMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneDebitMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneDebitMethodSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSender;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface;
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
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilter;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodFilterInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneFileReader;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneFileReaderInterface;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneInvoiceReader;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneInvoiceReaderInterface;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayonePaypalExpressCheckoutDetailsReader;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayonePaypalExpressCheckoutDetailsReaderInterface;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneSecurityInvoiceReader;
use SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneSecurityInvoiceReaderInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactory;
use SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapper;
use SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapperInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\RiskCheckManager;
use SprykerEco\Zed\Payone\Business\RiskManager\RiskCheckManagerInterface;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerEco\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use SprykerEco\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManagerInterface;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface;
use SprykerEco\Zed\Payone\PayoneDependencyProvider;
use Symfony\Component\HttpFoundation\RequestStack;

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
    public function createPayonePreAuthorizeMethodSender(): PayonePreAuthorizeMethodSenderInterface
    {
        return new PayonePreAuthorizeMethodSender(
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->createPayoneBaseAuthorizeSender(),
            $this->createAuthorizationResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneAuthorizeMethodSenderInterface
     */
    public function createPayoneAuthorizeMethodSender(): PayoneAuthorizeMethodSenderInterface
    {
        return new PayoneAuthorizeMethodSender(
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->createPayoneRequestProductDataMapper(),
            $this->createPayoneBaseAuthorizeSender(),
            $this->createAuthorizationResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneCaptureMethodSenderInterface
     */
    public function createPayoneCaptureMethodSender(): PayoneCaptureMethodSenderInterface
    {
        return new PayoneCaptureMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->createOrderPriceDistributor(),
            $this->createStandartParameterMapper(),
            $this->createPayoneRequestProductDataMapper(),
            $this->createExpenseMapper(),
            $this->createCaptureResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialCaptureMethodSenderInterface
     */
    public function createPayonePartialCaptureMethodSender(): PayonePartialCaptureMethodSenderInterface
    {
        return new PayonePartialCaptureMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->createExpenseMapper(),
            $this->getStandardParameter(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createOrderPriceDistributor(),
            $this->createStandartParameterMapper(),
            $this->createShipmentMapper(),
            $this->createCaptureResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneDebitMethodSenderInterface
     */
    public function createPayoneDebitMethodSender(): PayoneDebitMethodSenderInterface
    {
        return new PayoneDebitMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
            $this->createDebitResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneRefundMethodSenderInterface
     */
    public function createPayoneRefundMethodSender(): PayoneRefundMethodSenderInterface
    {
        return new PayoneRefundMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->createOrderPriceDistributor(),
            $this->createStandartParameterMapper(),
            $this->createPayoneRequestProductDataMapper(),
            $this->createRefundResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayonePartialRefundMethodSenderInterface
     */
    public function createPayonePartialRefundMethodSender(): PayonePartialRefundMethodSenderInterface
    {
        return new PayonePartialRefundMethodSender(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createStandartParameterMapper(),
            $this->createRefundResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Checker\PayoneBankAccountCheckerInterface
     */
    public function createPayoneBankAccountChecker(): PayoneBankAccountCheckerInterface
    {
        return new PayoneBankAccountChecker(
            $this->createExecutionAdapter(),
            $this->createPaymentMapperReader(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Checker\PayoneCreditCardCheckerInterface
     */
    public function createPayoneCreditCardChecker(): PayoneCreditCardCheckerInterface
    {
        return new PayoneCreditCardChecker(
            $this->createExecutionAdapter(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
            $this->createCreditCardCheckResponseMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneManageMandateMethodSenderInterface
     */
    public function createPayoneManageMandateMethodSender(): PayoneManageMandateMethodSenderInterface
    {
        return new PayoneManageMandateMethodSender(
            $this->createExecutionAdapter(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneFileReaderInterface
     */
    public function createPayoneFileReader(): PayoneFileReaderInterface
    {
        return new PayoneFileReader(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneInvoiceReaderInterface
     */
    public function createPayoneInvoiceReader(): PayoneInvoiceReaderInterface
    {
        return new PayoneInvoiceReader(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Reader\PayoneSecurityInvoiceReaderInterface
     */
    public function createPayoneSecurityInvoiceReader(): PayoneSecurityInvoiceReaderInterface
    {
        return new PayoneSecurityInvoiceReader(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
            $this->createPaymentMapperReader(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneInitPaypalExpressCheckoutMethodSenderInterface
     */
    public function createPayoneInitPaypalExpressCheckoutMethodSender(): PayoneInitPaypalExpressCheckoutMethodSenderInterface
    {
        return new PayoneInitPaypalExpressCheckoutMethodSender(
            $this->createPaymentMapperReader(),
            $this->createPayoneGenericRequestMethodSender(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Reader\PayonePaypalExpressCheckoutDetailsReaderInterface
     */
    public function createPayonePaypalExpressCheckoutDetailsReader(): PayonePaypalExpressCheckoutDetailsReaderInterface
    {
        return new PayonePaypalExpressCheckoutDetailsReader(
            $this->createPaymentMapperReader(),
            $this->createPayoneGenericRequestMethodSender()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface
     */
    public function createPayoneGenericRequestMethodSender(): PayoneGenericRequestMethodSenderInterface
    {
        return new PayoneGenericRequestMethodSender(
            $this->createExecutionAdapter(),
            $this->getStandardParameter(),
            $this->createStandartParameterMapper(),
        );
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
    public function createTransactionStatusManager(): TransactionStatusUpdateManagerInterface
    {
        return new TransactionStatusUpdateManager(
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createHashGenerator()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\ApiLog\ApiLogFinderInterface
     */
    public function createApiLogFinder(): ApiLogFinderInterface
    {
        return new ApiLogFinder(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    public function createExecutionAdapter(): AdapterInterface
    {
        return new Guzzle(
            $this->getStandardParameter()->getPaymentGatewayUrl(),
            $this->createApiCallLogWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriter
     */
    public function createApiCallLogWriter(): ApiCallLogWriter
    {
        return new ApiCallLogWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    public function createSequenceNumberProvider(): SequenceNumberProviderInterface
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
    public function createHashProvider(): HashInterface
    {
        return new HashProvider();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface
     */
    public function createHashGenerator(): HashGeneratorInterface
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
        return new ModeDetector($this->getConfig());
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest
     */
    public function createTransactionStatusRequest(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer): TransactionStatusRequest
    {
        return new TransactionStatusRequest($transactionStatusUpdateTransfer->toArray());
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

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo
     */
    public function createCreditCardPseudo($storeConfig): CreditCardPseudo
    {
        return new CreditCardPseudo($storeConfig);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit
     */
    public function createDirectDebit($storeConfig): DirectDebit
    {
        return new DirectDebit($storeConfig);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice
     */
    public function createInvoice($storeConfig): Invoice
    {
        return new Invoice($storeConfig);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    public function createSecurityInvoice($storeConfig): PaymentMethodMapperInterface
    {
        return new SecurityInvoice($storeConfig, $this->getConfig());
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer
     */
    public function createOnlineBankTransfer($storeConfig): OnlineBankTransfer
    {
        return new OnlineBankTransfer($storeConfig);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\EWallet
     */
    public function createEWallet($storeConfig): EWallet
    {
        return new EWallet($storeConfig);
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
    public function createPrepayment($storeConfig): Prepayment
    {
        return new Prepayment($storeConfig);
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\GenericPaymentMethodMapperInterface
     */
    public function createGenericPayment(Store $store): GenericPaymentMethodMapperInterface
    {
        return new GenericPayment($store);
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
        return new StandartParameterMapper($this->createHashGenerator(), $this->createModeDetector());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface
     */
    public function createPayoneRequestProductDataMapper(): PayoneRequestProductDataMapperInterface
    {
        return new PayoneRequestProductDataMapper(
            $this->createProductMapper(),
            $this->createShipmentMapper(),
            $this->createDiscountMapper(),
        );
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductMapperInterface
     */
    public function createProductMapper(): ProductMapperInterface
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
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    public function createPaymentMapperReader(): PaymentMapperReaderInterface
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
     * @return \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapperInterface
     */
    public function createExpenseMapper(): ExpenseMapperInterface
    {
        return new ExpenseMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\ConditionChecker\RefundCheckerInterface
     */
    public function createRefundChecker(): RefundCheckerInterface
    {
        return new RefundChecker($this->getRepository(), $this->createPaymentDataChecker());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\ConditionChecker\PaymentDataCheckerInterface
     */
    public function createPaymentDataChecker(): PaymentDataCheckerInterface
    {
        return new PaymentDataChecker($this->getRepository(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\Hook\PostSaveHookInterface
     */
    public function createPostSaveHook(): PostSaveHookInterface
    {
        return new PostSaveHook($this->getRepository());
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\RefundResponseMapperInterface
     */
    public function createRefundResponseMapper(): RefundResponseMapperInterface
    {
        return new RefundResponseMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface
     */
    public function createAuthorizationResponseMapper(): AuthorizationResponseMapperInterface
    {
        return new AuthorizationResponseMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapperInterface
     */
    public function createCaptureResponseMapper(): CaptureResponseMapperInterface
    {
        return new CaptureResponseMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CreditCardCheckResponseMapperInterface
     */
    public function createCreditCardCheckResponseMapper(): CreditCardCheckResponseMapperInterface
    {
        return new CreditCardCheckResponseMapper();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\DebitResponseMapperInterface
     */
    public function createDebitResponseMapper(): DebitResponseMapperInterface
    {
        return new DebitResponseMapper();
    }

    /**
     * @return array
     */
    public function getAvailablePaymentMethods(): array
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
    public function getStandardParameter(): PayoneStandardParameterTransfer
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): PayoneToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
