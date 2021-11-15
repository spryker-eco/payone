<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Reader;

use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\GetFileResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneFileReader implements PayoneFileReaderInterface
{
    /**
     * @var string
     */
    protected const ERROR_ACCESS_DENIED_MESSAGE = 'Access denied';

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
     * @var \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface|\SprykerEco\Zed\Payone\Business\Key\HashGenerator
     */
    protected $hashGenerator;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper,
        PaymentMapperReaderInterface $paymentMapperReader
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->paymentMapperReader = $paymentMapperReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $getFileTransfer): PayoneGetFileTransfer
    {
        $paymentEntity = $this->findPaymentByFileReferenceAndCustomerId(
            $getFileTransfer->getReference(),
            $getFileTransfer->getCustomerId(),
        );

        if (!$paymentEntity) {
            return $this->setAccessDeniedError($getFileTransfer);
        }

        $responseContainer = $this->fetchFile($getFileTransfer);

        $getFileTransfer->setRawResponse($responseContainer->getRawResponse());
        $getFileTransfer->setStatus($responseContainer->getStatus());
        $getFileTransfer->setErrorCode($responseContainer->getErrorcode());
        $getFileTransfer->setCustomerErrorMessage($responseContainer->getCustomermessage());
        $getFileTransfer->setInternalErrorMessage($responseContainer->getErrormessage());

        return $getFileTransfer;
    }

    /**
     * @param string $fileReference
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone|null
     */
    protected function findPaymentByFileReferenceAndCustomerId(string $fileReference, int $customerId): ?SpyPaymentPayone
    {
        return $this->queryContainer->createPaymentByFileReferenceAndCustomerIdQuery($fileReference, $customerId)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    protected function setAccessDeniedError(PayoneGetFileTransfer $getFileTransfer): PayoneGetFileTransfer
    {
        $getFileTransfer->setStatus(PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $getFileTransfer->setInternalErrorMessage(static::ERROR_ACCESS_DENIED_MESSAGE);
        $getFileTransfer->setCustomerErrorMessage(static::ERROR_ACCESS_DENIED_MESSAGE);

        return $getFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\GetFileResponseContainer
     */
    protected function fetchFile(PayoneGetFileTransfer $getFileTransfer): GetFileResponseContainer
    {
        $responseContainer = new GetFileResponseContainer();

        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper(PayoneApiConstants::PAYMENT_METHOD_DIRECT_DEBIT);
        $requestContainer = $paymentMethodMapper->mapGetFile($getFileTransfer);
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);
        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer->init($rawResponse);

        return $responseContainer;
    }
}
