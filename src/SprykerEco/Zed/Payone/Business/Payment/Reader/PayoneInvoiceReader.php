<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Reader;

use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\GetInvoiceResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayoneInvoiceReader implements PayoneInvoiceReaderInterface
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
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standardParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standardParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneRepositoryInterface $payoneRepository,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standardParameterMapper,
        PaymentMapperReaderInterface $paymentMapperReader
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->payoneRepository = $payoneRepository;
        $this->standardParameter = $standardParameter;
        $this->standardParameterMapper = $standardParameterMapper;
        $this->paymentMapperReader = $paymentMapperReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer): PayoneGetInvoiceTransfer
    {
        $paymentPayoneEntity = $this->findPaymentByInvoiceTitleAndCustomerId(
            $getInvoiceTransfer->getReferenceOrFail(),
            $getInvoiceTransfer->getCustomerIdOrFail(),
        );

        if (!$paymentPayoneEntity) {
            return $this->setAccessDeniedError($getInvoiceTransfer);
        }

        $responseContainer = $this->fetchInvoice($getInvoiceTransfer);

        $getInvoiceTransfer->setRawResponse($responseContainer->getRawResponse());
        $getInvoiceTransfer->setStatus($responseContainer->getStatus());
        $getInvoiceTransfer->setErrorCode($responseContainer->getErrorcode());
        $getInvoiceTransfer->setInternalErrorMessage($responseContainer->getErrormessage());

        return $getInvoiceTransfer;
    }

    /**
     * @param string $invoiceTitle
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone|null
     */
    protected function findPaymentByInvoiceTitleAndCustomerId(string $invoiceTitle, int $customerId): ?SpyPaymentPayone
    {
        return $this->payoneRepository->createPaymentByInvoiceTitleAndCustomerIdQuery($invoiceTitle, $customerId)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    protected function setAccessDeniedError(PayoneGetInvoiceTransfer $getInvoiceTransfer): PayoneGetInvoiceTransfer
    {
        $getInvoiceTransfer->setStatus(PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $getInvoiceTransfer->setInternalErrorMessage(static::ERROR_ACCESS_DENIED_MESSAGE);
        $getInvoiceTransfer->setCustomerErrorMessage(static::ERROR_ACCESS_DENIED_MESSAGE);

        return $getInvoiceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\GetInvoiceResponseContainer
     */
    protected function fetchInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer): GetInvoiceResponseContainer
    {
        $responseContainer = new GetInvoiceResponseContainer();

        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $requestContainer = $paymentMethodMapper->mapGetInvoice($getInvoiceTransfer);
        $this->standardParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);
        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer->init($rawResponse);

        return $responseContainer;
    }
}
