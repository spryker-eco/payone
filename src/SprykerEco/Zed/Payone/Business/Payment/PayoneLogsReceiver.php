<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer;
use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayoneLogsReceiver implements PayoneLogsReceiverInterface
{
    public const LOG_TYPE_API_LOG = 'SpyPaymentPayoneApiLog';
    public const LOG_TYPE_TRANSACTION_STATUS_LOG = 'SpyPaymentPayoneTransactionStatusLog';
    public const ERROR_ACCESS_DENIED_MESSAGE = 'Access denied';

    protected const PARAMETER_KEY_CAPTURE_MODE = 'capturemode';
    protected const PARAMETER_VALUE_CAPTURE_COMPLETED = 'completed';

    /**
     * @see \Spryker\Shared\Shipment\ShipmentConstants::SHIPMENT_EXPENSE_TYPE
     * @see \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @deprecated Necessary in order to save compatibility with spryker/shipping version less than "^8.0.0".
     * use \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE instead if shipping version is higher
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';
    protected const SHIPMENT_PRODUCT_DESCRIPTION = 'Shipment';
    protected const ZERRO_DELIVERY_COSTS = 0;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface|\SprykerEco\Zed\Payone\Business\Key\HashGenerator
     */
    protected $hashGenerator;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface
     */
    protected $payoneEntityManager;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface
     */
    protected $orderPriceDistributor;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface
     */
    protected $productsMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface
     */
    protected $discountMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager
     */
    protected $paymentMapperManager;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Key\HashGenerator $hashGenerator
     * @param \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface $payoneEntityManager
     * @param \SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface $orderPriceDistributor
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface $productsMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface $shipmentMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface $discountMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        HashGenerator $hashGenerator,
        ModeDetectorInterface $modeDetector,
        PayoneRepositoryInterface $payoneRepository,
        PayoneEntityManagerInterface $payoneEntityManager,
        OrderPriceDistributorInterface $orderPriceDistributor,
        StandartParameterMapperInterface $standartParameterMapper,
        ProductsMapperInterface $productsMapper,
        ShipmentMapperInterface $shipmentMapper,
        DiscountMapperInterface $discountMapper,
        PaymentMapperManager $paymentMapperManager
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
        $this->payoneRepository = $payoneRepository;
        $this->payoneEntityManager = $payoneEntityManager;
        $this->orderPriceDistributor = $orderPriceDistributor;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->productsMapper = $productsMapper;
        $this->shipmentMapper = $shipmentMapper;
        $this->discountMapper = $discountMapper;
        $this->paymentMapperManager = $paymentMapperManager;
    }

    /**
     * Gets payment logs (both api and transaction status) for specific orders in chronological order.
     *
     * @param \Propel\Runtime\Collection\ObjectCollection|\ArrayObject $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogCollectionTransfer
     */
    public function getPaymentLogs($orders): PayonePaymentLogCollectionTransfer
    {
        $apiLogs = $this->queryContainer->createApiLogsByOrderIds($orders)->find()->getData();

        $transactionStatusLogs = $this->queryContainer->createTransactionStatusLogsByOrderIds($orders)->find()->getData();

        $logs = [];
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLog */
        foreach ($apiLogs as $apiLog) {
            $key = $apiLog->getCreatedAt()->format('Y-m-d\TH:i:s\Z') . 'a' . $apiLog->getIdPaymentPayoneApiLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($apiLog->toArray(), true);
            $payonePaymentLogTransfer->setLogType(self::LOG_TYPE_API_LOG);
            $logs[$key] = $payonePaymentLogTransfer;
        }
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
        foreach ($transactionStatusLogs as $transactionStatusLog) {
            $key = $transactionStatusLog->getCreatedAt()->format('Y-m-d\TH:i:s\Z') . 't' . $transactionStatusLog->getIdPaymentPayoneTransactionStatusLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($transactionStatusLog->toArray(), true);
            $payonePaymentLogTransfer->setLogType(self::LOG_TYPE_TRANSACTION_STATUS_LOG);
            $logs[$key] = $payonePaymentLogTransfer;
        }

        ksort($logs);

        $payonePaymentLogCollectionTransfer = new PayonePaymentLogCollectionTransfer();

        foreach ($logs as $log) {
            $payonePaymentLogCollectionTransfer->addPaymentLog($log);
        }

        return $payonePaymentLogCollectionTransfer;
    }
}
