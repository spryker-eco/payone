<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\GetSecurityInvoiceResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneGetSecurityInvoiceMethodSender implements PayoneGetSecurityInvoiceMethodSenderInterface
{
    public const ERROR_ACCESS_DENIED_MESSAGE = 'Access denied';

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
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager
     */
    protected $paymentMapperManager;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper,
        PaymentMapperManager $paymentMapperManager
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->paymentMapperManager = $paymentMapperManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): PayoneGetSecurityInvoiceTransfer
    {
        $responseContainer = new GetSecurityInvoiceResponseContainer();
        $paymentEntity = $this->findPaymentByInvoiceTitleAndCustomerId(
            $getSecurityInvoiceTransfer->getReference(),
            $getSecurityInvoiceTransfer->getCustomerId()
        );

        if (!$paymentEntity) {
            $this->setAccessDeniedError($responseContainer);

            return $getSecurityInvoiceTransfer;
        }

        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\SecurityInvoice $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperManager->getRegisteredPaymentMethodMapper(PayoneApiConstants::PAYMENT_METHOD_SECURITY_INVOICE);
        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer */
        $requestContainer = $paymentMethodMapper->mapGetSecurityInvoice($getSecurityInvoiceTransfer);
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);
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
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function findPaymentByInvoiceTitleAndCustomerId($invoiceTitle, $customerId)
    {
        return $this->queryContainer->createPaymentByInvoiceTitleAndCustomerIdQuery($invoiceTitle, $customerId)->findOne();
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer $container
     *
     * @return void
     */
    protected function setAccessDeniedError(AbstractResponseContainer $container)
    {
        $container->setStatus(PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $container->setErrormessage(static::ERROR_ACCESS_DENIED_MESSAGE);
        $container->setCustomermessage(static::ERROR_ACCESS_DENIED_MESSAGE);
    }
}
