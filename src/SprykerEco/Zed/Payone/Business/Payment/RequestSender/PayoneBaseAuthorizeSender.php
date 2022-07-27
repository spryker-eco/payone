<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneBaseAuthorizeSender extends AbstractPayoneRequestSender implements PayoneBaseAuthorizeSenderInterface
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
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standardParameterMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standardParameterMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standardParameterMapper
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->standardParameterMapper = $standardParameterMapper;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
     */
    public function performAuthorizationRequest(
        SpyPaymentPayone $paymentPayoneEntity,
        AbstractRequestContainer $requestContainer
    ): AuthorizationResponseContainer {
        $this->standardParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $apiLogEntity = $this->initializeApiLog($paymentPayoneEntity, $requestContainer);
        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new AuthorizationResponseContainer($rawResponse);
        $this->updatePaymentAfterAuthorization($paymentPayoneEntity, $responseContainer);
        $this->updateApiLogAfterAuthorization($apiLogEntity, $responseContainer);
        $this->updatePaymentDetailAfterAuthorization($paymentPayoneEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updatePaymentAfterAuthorization(SpyPaymentPayone $paymentPayoneEntity, AuthorizationResponseContainer $responseContainer): void
    {
        $paymentPayoneEntity->setTransactionId($responseContainer->getTxid());
        $paymentPayoneEntity->save();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updateApiLogAfterAuthorization(SpyPaymentPayoneApiLog $apiLogEntity, AuthorizationResponseContainer $responseContainer): void
    {
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setUserId((string)$responseContainer->getUserid());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());
        $apiLogEntity->setRedirectUrl($responseContainer->getRedirecturl());
        $apiLogEntity->setSequenceNumber(0);

        $apiLogEntity->setRawResponse((string)json_encode($responseContainer->toArray()));
        $apiLogEntity->save();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updatePaymentDetailAfterAuthorization(SpyPaymentPayone $paymentPayoneEntity, AuthorizationResponseContainer $responseContainer): void
    {
        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        $paymentDetailEntity->setClearingBankAccountHolder($responseContainer->getClearingBankaccountholder());
        $paymentDetailEntity->setClearingBankCountry($responseContainer->getClearingBankcountry());
        $paymentDetailEntity->setClearingBankAccount($responseContainer->getClearingBankaccount());
        $paymentDetailEntity->setClearingBankCode($responseContainer->getClearingBankcode());
        $paymentDetailEntity->setClearingBankIban($responseContainer->getClearingBankiban());
        $paymentDetailEntity->setClearingBankBic($responseContainer->getClearingBankbic());
        $paymentDetailEntity->setClearingBankCity($responseContainer->getClearingBankcity());
        $paymentDetailEntity->setClearingBankName($responseContainer->getClearingBankname());

        if ($responseContainer->getMandateIdentification()) {
            $paymentDetailEntity->setMandateIdentification($responseContainer->getMandateIdentification());
        }

        if ($paymentPayoneEntity->getPaymentMethod() == PayoneApiConstants::PAYMENT_METHOD_INVOICE) {
            $invoiceTitle = $this->getInvoiceTitle($paymentPayoneEntity->getTransactionId());
            $paymentDetailEntity->setInvoiceTitle($invoiceTitle);
        }

        $paymentDetailEntity->save();
    }

    /**
     * @param int|null $transactionId
     *
     * @return string
     */
    public function getInvoiceTitle(?int $transactionId): string
    {
        return implode('-', [
            PayoneApiConstants::INVOICE_TITLE_PREFIX_INVOICE,
            $transactionId,
            0,
        ]);
    }
}
