<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneBaseAuthorizeSender extends AbstractPayoneMethodSender implements PayoneBaseAuthorizeSenderInterface
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
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperManager $paymentMapperManager,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper
    ) {
        parent::__construct($queryContainer, $paymentMapperManager);
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
     */
    public function performAuthorizationRequest(SpyPaymentPayone $paymentEntity, AuthorizationContainerInterface $requestContainer): AuthorizationResponseContainer
    {
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);
        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new AuthorizationResponseContainer($rawResponse);
        $this->updatePaymentAfterAuthorization($paymentEntity, $responseContainer);
        $this->updateApiLogAfterAuthorization($apiLogEntity, $responseContainer);
        $this->updatePaymentDetailAfterAuthorization($paymentEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updatePaymentAfterAuthorization(SpyPaymentPayone $paymentEntity, AuthorizationResponseContainer $responseContainer)
    {
        $paymentEntity->setTransactionId($responseContainer->getTxid());
        $paymentEntity->save();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updateApiLogAfterAuthorization(SpyPaymentPayoneApiLog $apiLogEntity, AuthorizationResponseContainer $responseContainer)
    {
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setUserId($responseContainer->getUserid());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());
        $apiLogEntity->setRedirectUrl($responseContainer->getRedirecturl());
        $apiLogEntity->setSequenceNumber(0);

        $apiLogEntity->setRawResponse(json_encode($responseContainer->toArray()));
        $apiLogEntity->save();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updatePaymentDetailAfterAuthorization(SpyPaymentPayone $paymentEntity, AuthorizationResponseContainer $responseContainer)
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

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

        if ($paymentEntity->getPaymentMethod() == PayoneApiConstants::PAYMENT_METHOD_INVOICE) {
            $invoiceTitle = $this->getInvoiceTitle($paymentEntity->getTransactionId());
            $paymentDetailEntity->setInvoiceTitle($invoiceTitle);
        }

        $paymentDetailEntity->save();
    }

    /**
     * @param int $transactionId
     *
     * @return string
     */
    public function getInvoiceTitle($transactionId)
    {
        return implode('-', [
            PayoneApiConstants::INVOICE_TITLE_PREFIX_INVOICE,
            $transactionId,
            0,
        ]);
    }
}
