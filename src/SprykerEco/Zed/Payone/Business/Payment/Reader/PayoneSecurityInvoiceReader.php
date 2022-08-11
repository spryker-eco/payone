<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Reader;

use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\GetSecurityInvoiceResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayoneSecurityInvoiceReader implements PayoneSecurityInvoiceReaderInterface
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
    protected $queryContainer;

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
        $this->queryContainer = $payoneRepository;
        $this->standardParameter = $standardParameter;
        $this->standardParameterMapper = $standardParameterMapper;
        $this->paymentMapperReader = $paymentMapperReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): PayoneGetSecurityInvoiceTransfer
    {
        $responseContainer = new GetSecurityInvoiceResponseContainer();
        $paymentPayoneEntity = $this->findPaymentByInvoiceTitleAndCustomerId(
            $getSecurityInvoiceTransfer->getReferenceOrFail(),
            $getSecurityInvoiceTransfer->getCustomerIdOrFail(),
        );

        if (!$paymentPayoneEntity) {
            return $this->setAccessDeniedError($getSecurityInvoiceTransfer);
        }

        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\SecurityInvoice $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper(PayoneApiConstants::PAYMENT_METHOD_SECURITY_INVOICE);
        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer */
        $requestContainer = $paymentMethodMapper->mapGetSecurityInvoice($getSecurityInvoiceTransfer);
        $this->standardParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);
        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer->init($rawResponse);

        $getSecurityInvoiceTransfer->setRawResponse($responseContainer->getRawResponse());
        $getSecurityInvoiceTransfer->setStatus($responseContainer->getStatus());
        $getSecurityInvoiceTransfer->setErrorCode($responseContainer->getErrorcode());
        $getSecurityInvoiceTransfer->setInternalErrorMessage($responseContainer->getErrormessage());

        return $getSecurityInvoiceTransfer;
    }

    /**
     * @param string $invoiceTitle
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone|null
     */
    protected function findPaymentByInvoiceTitleAndCustomerId(string $invoiceTitle, int $customerId): ?SpyPaymentPayone
    {
        return $this->queryContainer->createPaymentByInvoiceTitleAndCustomerIdQuery($invoiceTitle, $customerId)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    protected function setAccessDeniedError(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): PayoneGetSecurityInvoiceTransfer
    {
        $getSecurityInvoiceTransfer->setStatus(PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $getSecurityInvoiceTransfer->setInternalErrorMessage(static::ERROR_ACCESS_DENIED_MESSAGE);
        $getSecurityInvoiceTransfer->setCustomerErrorMessage(static::ERROR_ACCESS_DENIED_MESSAGE);

        return $getSecurityInvoiceTransfer;
    }
}
