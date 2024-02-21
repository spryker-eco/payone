<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\PayoneCaptureTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneCaptureRequestSender extends AbstractPayoneRequestSender implements PayoneCaptureRequestSenderInterface
{
    /**
     * @deprecated Necessary in order to save compatibility with spryker/shipping version less than "^8.0.0".
     * use \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE instead if shipping version is higher
     *
     * @see \Spryker\Shared\Shipment\ShipmentConstants::SHIPMENT_EXPENSE_TYPE
     * @see \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface
     */
    protected $orderPriceDistributor;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface
     */
    protected $payoneRequestProductDataMapper;

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
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface $orderPriceDistributor
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ExpenseMapperInterface $expenseMapper
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CaptureResponseMapperInterface $captureResponseMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneStandardParameterTransfer $standardParameter,
        OrderPriceDistributorInterface $orderPriceDistributor,
        StandartParameterMapperInterface $standartParameterMapper,
        PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper,
        ExpenseMapperInterface $expenseMapper,
        CaptureResponseMapperInterface $captureResponseMapper
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->orderPriceDistributor = $orderPriceDistributor;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->payoneRequestProductDataMapper = $payoneRequestProductDataMapper;
        $this->expenseMapper = $expenseMapper;
        $this->captureResponseMapper = $captureResponseMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneCaptureTransfer $captureTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function capturePayment(PayoneCaptureTransfer $captureTransfer): CaptureResponseTransfer
    {
        $captureTransfer = $this->addDataToCaptureTransferFromDistributedOrder($captureTransfer);

        $paymentEntity = $this->getPaymentEntity($captureTransfer->getPayment()->getFkSalesOrder());
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);

        $requestContainer = $paymentMethodMapper->mapPaymentToCapture($paymentEntity);

        if ($captureTransfer->getAmount()) {
            $requestContainer = $this->payoneRequestProductDataMapper->mapProductData($captureTransfer->getOrder(), $requestContainer);
            $requestContainer = $this->expenseMapper->mapExpenses($captureTransfer->getOrder(), $requestContainer);
        }

        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer $requestContainer */
        if (!empty($captureTransfer->getSettleaccount())) {
            $businnessContainer = new BusinessContainer();
            $businnessContainer->setSettleAccount($captureTransfer->getSettleaccount());
            $requestContainer->setBusiness($businnessContainer);
        }

        if ($captureTransfer->getAmount() !== null) {
            $requestContainer->setAmount($captureTransfer->getAmount());
        }

        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);

        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);
        $responseContainer = new CaptureResponseContainer($rawResponse);
        $this->updateApiLogAfterCapture($apiLogEntity, $responseContainer);

        return $this->captureResponseMapper->getCaptureResponseTransfer($responseContainer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneCaptureTransfer $captureTransfer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    protected function addDataToCaptureTransferFromDistributedOrder(PayoneCaptureTransfer $captureTransfer): CaptureResponseTransfer
    {
        $distributedPriceOrderTransfer = $this->orderPriceDistributor->distributeOrderPrice(
            $captureTransfer->getOrder()
        );

        $captureTransfer->setOrder($distributedPriceOrderTransfer);

        if ($captureTransfer->getAmount() === null) {
            $captureTransfer->setAmount($distributedPriceOrderTransfer->getTotals()->getGrandTotal());
        }

        return $captureTransfer;
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
}
