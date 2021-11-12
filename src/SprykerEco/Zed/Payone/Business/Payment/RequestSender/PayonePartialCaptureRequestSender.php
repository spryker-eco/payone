<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneOrderItemFilterTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use Spryker\Shared\Shipment\ShipmentConfig;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneTransactionStatusConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayonePartialCaptureRequestSender extends AbstractPayoneRequestSender implements PayonePartialCaptureRequestSenderInterface
{
    public const ITEM_STATE_SHIPPED = 'shipped';
    
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
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapperInterface
     */
    protected $expenseMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapperInterface
     */
    protected $captureResponseMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapperInterface $expenseMapper
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface $payoneEntityManager
     * @param \SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface $orderPriceDistributor
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface $shipmentMapper
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapperInterface $captureResponseMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReaderInterface $paymentMapperReader,
        ExpenseMapperInterface $expenseMapper,
        PayoneStandardParameterTransfer $standardParameter,
        PayoneRepositoryInterface $payoneRepository,
        PayoneEntityManagerInterface $payoneEntityManager,
        OrderPriceDistributorInterface $orderPriceDistributor,
        StandartParameterMapperInterface $standartParameterMapper,
        ShipmentMapperInterface $shipmentMapper,
        CaptureResponseMapperInterface $captureResponseMapper
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->payoneRepository = $payoneRepository;
        $this->payoneEntityManager = $payoneEntityManager;
        $this->orderPriceDistributor = $orderPriceDistributor;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->shipmentMapper = $shipmentMapper;
        $this->expenseMapper = $expenseMapper;
        $this->captureResponseMapper = $captureResponseMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function executePartialCapture(
        PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
    ): CaptureResponseTransfer {
        $distributedPriceOrderTransfer = $this->orderPriceDistributor->distributeOrderPrice(
            $payonePartialOperationRequestTransfer->getOrder()
        );
        $payonePartialOperationRequestTransfer->setOrder($distributedPriceOrderTransfer);

        $paymentEntity = $this->getPaymentEntity($payonePartialOperationRequestTransfer->getOrder()->getIdSalesOrder());
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);

        $requestContainer = $paymentMethodMapper->mapPaymentToCapture($paymentEntity);
        $requestContainer = $this->preparePartialCaptureOrderItems($payonePartialOperationRequestTransfer, $requestContainer);
        $captureAmount = $this->calculatePartialCaptureItemsAmount($payonePartialOperationRequestTransfer);

        if ($this->isNeedToAddDeliveryCosts($payonePartialOperationRequestTransfer)) {
            $captureAmount += $this->getDeliveryCosts($payonePartialOperationRequestTransfer->getOrder());
            $requestContainer = $this->shipmentMapper->mapShipment($payonePartialOperationRequestTransfer->getOrder(), $requestContainer);
        }
        
        $captureAmount += $this->calculateExpensesCost($payonePartialOperationRequestTransfer->getOrder());
        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer $requestContainer */
        $requestContainer = $this->expenseMapper->mapExpenses($payonePartialOperationRequestTransfer->getOrder(), $requestContainer);
        $businessContainer = new BusinessContainer();
        $businessContainer->setSettleAccount(PayoneApiConstants::SETTLE_ACCOUNT_YES);
        $requestContainer->setBusiness($businessContainer);

        $requestContainer->setAmount($captureAmount);
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new CaptureResponseContainer($rawResponse);

        $this->updateApiLogAfterCapture($apiLogEntity, $responseContainer);
        $this->updatePaymentPayoneOrderItemsWithStatus(
            $payonePartialOperationRequestTransfer,
            $this->getPartialCaptureStatus($responseContainer)
        );

        return $this->captureResponseMapper->getCaptureResponseTransfer($responseContainer);
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updateApiLogAfterCapture(SpyPaymentPayoneApiLog $apiLogEntity, CaptureResponseContainer $responseContainer): void
    {
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());

        $apiLogEntity->setRawResponse(json_encode($responseContainer->toArray()));
        $apiLogEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    protected function preparePartialCaptureOrderItems(
        PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer,
        AbstractRequestContainer $container
    ): AbstractRequestContainer {
        $arrayIt = [];
        $arrayId = [];
        $arrayPr = [];
        $arrayNo = [];
        $arrayDe = [];
        $arrayVa = [];

        $key = 1;

        foreach ($payonePartialOperationRequestTransfer->getOrder()->getItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getIdSalesOrderItem(), $payonePartialOperationRequestTransfer->getSalesOrderItemIds())) {
                continue;
            }

            $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS;
            $arrayId[$key] = substr($itemTransfer->getSku(), 0, 32);
            $arrayPr[$key] = $itemTransfer->getUnitPriceToPayAggregation();
            $arrayNo[$key] = $itemTransfer->getQuantity();
            $arrayDe[$key] = $itemTransfer->getName();
            $arrayVa[$key] = (int)$itemTransfer->getTaxRate();
            $key++;
        }

        $container->setIt($arrayIt);
        $container->setId($arrayId);
        $container->setPr($arrayPr);
        $container->setNo($arrayNo);
        $container->setDe($arrayDe);
        $container->setVa($arrayVa);

        return $container;
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return int
     */
    protected function calculatePartialCaptureItemsAmount(PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer): int
    {
        $itemsAmount = 0;
        foreach ($payonePartialOperationRequestTransfer->getOrder()->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $payonePartialOperationRequestTransfer->getSalesOrderItemIds())) {
                $itemsAmount += $itemTransfer->getSumPriceToPayAggregation();
            }
        }

        return $itemsAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getDeliveryCosts(OrderTransfer $orderTransfer): int
    {
        foreach ($orderTransfer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                return $expense->getSumGrossPrice();
            }
        }

        return 0;
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return bool
     */
    protected function isNeedToAddDeliveryCosts(PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer): bool
    {
        foreach ($payonePartialOperationRequestTransfer->getOrder()->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $payonePartialOperationRequestTransfer->getSalesOrderItemIds())) {
                continue;
            }
            foreach ($itemTransfer->getStateHistory() as $itemState) {
                if ($itemState->getName() === self::ITEM_STATE_SHIPPED) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function calculateExpensesCost(OrderTransfer $orderTransfer): int
    {
        $expensesCostAmount = 0;

        foreach ($orderTransfer->getExpenses() as $expense) {
            if ($expense->getType() !== ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                $expensesCostAmount += $expense->getSumGrossPrice();
            }
        }

        return $expensesCostAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     * @param string $refundStatus
     *
     * @return void
     */
    protected function updatePaymentPayoneOrderItemsWithStatus(
        PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer,
        string $refundStatus
    ): void {
        $payoneOrderItemFilterTransfer = (new PayoneOrderItemFilterTransfer())
            ->setIdSalesOrder($payonePartialOperationRequestTransfer->getOrder()->getIdSalesOrder())
            ->setSalesOrderItemIds($payonePartialOperationRequestTransfer->getSalesOrderItemIds());

        $payoneOrderItemTransfers = $this->payoneRepository->findPaymentPayoneOrderItemByFilter($payoneOrderItemFilterTransfer);

        foreach ($payoneOrderItemTransfers as $payoneOrderItemTransfer) {
            $payoneOrderItemTransfer->setStatus($refundStatus);
            $this->payoneEntityManager->updatePaymentPayoneOrderItem($payoneOrderItemTransfer);
        }
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer $responseContainer
     *
     * @return string
     */
    protected function getPartialCaptureStatus(CaptureResponseContainer $responseContainer): string
    {
        if ($responseContainer->getStatus() === PayoneApiConstants::RESPONSE_TYPE_APPROVED) {
            return PayoneTransactionStatusConstants::STATUS_CAPTURE_APPROVED;
        }

        return PayoneTransactionStatusConstants::STATUS_CAPTURE_FAILED;
    }
}
