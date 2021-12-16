<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\DebitResponseTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\DebitResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneDebitRequestSender extends AbstractPayoneRequestSender implements PayoneDebitRequestSenderInterface
{
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
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\DebitResponseMapperInterface
     */
    protected $debitResponseMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\DebitResponseMapperInterface $debitResponseMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper,
        PaymentMapperReaderInterface $paymentMapperReader,
        DebitResponseMapperInterface $debitResponseMapper
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->debitResponseMapper = $debitResponseMapper;
    }

    /**
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function debitPayment(int $idPayment): DebitResponseTransfer
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);

        $requestContainer = $paymentMethodMapper->mapPaymentToDebit($paymentEntity);
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $paymentEntity = $this->findPaymentByTransactionId($paymentEntity->getTransactionId() ?? 0);
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new DebitResponseContainer($rawResponse);

        $this->updateApiLogAfterDebit($apiLogEntity, $responseContainer);

        return $this->debitResponseMapper->getDebitResponseTransfer($responseContainer);
    }

    /**
     * @param int|null $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone|null
     */
    protected function findPaymentByTransactionId(?int $transactionId): ?SpyPaymentPayone
    {
        return !empty($transactionId) ? $this->queryContainer->createPaymentByTransactionIdQuery($transactionId)->findOne() : null;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updateApiLogAfterDebit(SpyPaymentPayoneApiLog $apiLogEntity, DebitResponseContainer $responseContainer): void
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
