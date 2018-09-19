<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business;

use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Psr\Log\LoggerInterface as MessengerInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\Http\Guzzle;
use SprykerEco\Zed\Payone\Business\Api\Log\ApiCallLogWriter;
use SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerEco\Zed\Payone\Business\ApiLog\ApiLogFinder;
use SprykerEco\Zed\Payone\Business\Internal\Installer;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Key\HashProvider;
use SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator;
use SprykerEco\Zed\Payone\Business\Mode\ModeDetector;
use SprykerEco\Zed\Payone\Business\Order\OrderManager;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\EWallet;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\GenericPayment;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Prepayment;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\SecurityInvoice;
use SprykerEco\Zed\Payone\Business\Payment\PaymentManager;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;
use SprykerEco\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use SprykerEco\Zed\Payone\PayoneDependencyProvider;

/**
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
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
    public function createPaymentManager()
    {
        $paymentManager = new PaymentManager(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createKeyHashGenerator(),
            $this->createSequenceNumberProvider(),
            $this->createModeDetector(),
            $this->createUrlHmacGenerator()
        );

        foreach ($this->getAvailablePaymentMethods() as $paymentMethod) {
            $paymentManager->registerPaymentMethodMapper($paymentMethod);
        }

        return $paymentManager;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Order\OrderManagerInterface
     */
    public function createOrderManager()
    {
        $orderManager = new OrderManager($this->getConfig());

        return $orderManager;
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
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\SecurityInvoice
     */
    protected function createSecurityInvoice($storeConfig)
    {
        $invoice = new SecurityInvoice($storeConfig);

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
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Prepayment
     */
    protected function createPrepayment($storeConfig)
    {
        $prepayment = new Prepayment($storeConfig);

        return $prepayment;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return \Spryker\Zed\Ratepay\Business\Internal\Install
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $installer = new Installer(
            $this->getGlossaryFacade(),
            $this->getConfig()
        );
        $installer->setMessenger($messenger);

        return $installer;
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
}
