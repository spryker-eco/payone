<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneOrderItemFilterTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use Spryker\Shared\Shipment\ShipmentConfig;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneTransactionStatusConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\RefundResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayonePartialRefundMethodSender extends AbstractPayoneRequestSender implements PayonePartialRefundMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

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
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\RefundResponseMapperInterface
     */
    protected $refundResponseMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface $payoneEntityManager
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\RefundResponseMapperInterface $refundResponseMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneStandardParameterTransfer $standardParameter,
        PayoneRepositoryInterface $payoneRepository,
        PayoneEntityManagerInterface $payoneEntityManager,
        StandartParameterMapperInterface $standartParameterMapper,
        RefundResponseMapperInterface $refundResponseMapper
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->payoneRepository = $payoneRepository;
        $this->payoneEntityManager = $payoneEntityManager;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->refundResponseMapper = $refundResponseMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function executePartialRefund(PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer): RefundResponseTransfer
    {
        $paymentEntity = $this->getPaymentEntity($payonePartialOperationRequestTransfer->getOrder()->getIdSalesOrder());
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToRefund($paymentEntity);

        $requestContainer->setAmount($payonePartialOperationRequestTransfer->getRefund()->getAmount() * -1);
        $requestContainer = $this->preparePartialRefundOrderItems($payonePartialOperationRequestTransfer, $requestContainer);
        $requestContainer = $this->preparePartialRefundExpenses($payonePartialOperationRequestTransfer, $requestContainer);
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new RefundResponseContainer($rawResponse);

        $this->updateApiLogAfterRefund($apiLogEntity, $responseContainer);
        $this->updatePaymentPayoneOrderItemsWithStatus(
            $payonePartialOperationRequestTransfer,
            $this->getPartialRefundStatus($responseContainer)
        );

        return $this->refundResponseMapper->getRefundResponseTransfer($responseContainer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    protected function preparePartialRefundOrderItems(
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
            $arrayId[$key] = $itemTransfer->getSku();
            $arrayPr[$key] = $itemTransfer->getRefundableAmount();
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
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    protected function preparePartialRefundExpenses(
        PayonePartialOperationRequestTransfer $payonePartialOperationRequestTransfer,
        AbstractRequestContainer $container
    ): AbstractRequestContainer {
        $arrayIt = $container->getIt() ?? [];
        $arrayId = $container->getId() ?? [];
        $arrayPr = $container->getPr() ?? [];
        $arrayNo = $container->getNo() ?? [];
        $arrayDe = $container->getDe() ?? [];
        $arrayVa = $container->getVa() ?? [];

        $key = count($arrayId) + 1;

        foreach ($payonePartialOperationRequestTransfer->getRefund()->getExpenses() as $expenseTransfer) {
            $expenseType = PayoneApiConstants::INVOICING_ITEM_TYPE_HANDLING;
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                $expenseType = PayoneApiConstants::INVOICING_ITEM_TYPE_SHIPMENT;
            }

            $arrayIt[$key] = $expenseType;
            $arrayId[$key] = $expenseType;
            $arrayPr[$key] = $expenseTransfer->getRefundableAmount();
            $arrayNo[$key] = $expenseTransfer->getQuantity();
            $arrayDe[$key] = $expenseTransfer->getName();
            $arrayVa[$key] = (int)$expenseTransfer->getTaxRate();
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updateApiLogAfterRefund(SpyPaymentPayoneApiLog $apiLogEntity, RefundResponseContainer $responseContainer): void
    {
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());

        $apiLogEntity->setRawResponse(json_encode($responseContainer->toArray()));
        $apiLogEntity->save();
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
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer $responseContainer
     *
     * @return string
     */
    protected function getPartialRefundStatus(RefundResponseContainer $responseContainer): string
    {
        if ($responseContainer->getStatus() === PayoneApiConstants::RESPONSE_TYPE_APPROVED) {
            return PayoneTransactionStatusConstants::STATUS_REFUND_APPROVED;
        }

        return PayoneTransactionStatusConstants::STATUS_REFUND_FAILED;
    }
}
