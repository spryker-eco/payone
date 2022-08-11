<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayonePreAuthorizeRequestSender extends AbstractPayoneRequestSender implements PayonePreAuthorizeRequestSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\RequestSender\PayoneBaseAuthorizeSenderInterface
     */
    protected $baseAuthorizeSender;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface
     */
    protected $authorizationResponseMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Payment\RequestSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface $authorizationResponseMapper
     */
    public function __construct(
        PayoneRepositoryInterface $payoneRepository,
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender,
        AuthorizationResponseMapperInterface $authorizationResponseMapper
    ) {
        parent::__construct($payoneRepository, $paymentMapperReader);
        $this->baseAuthorizeSender = $baseAuthorizeSender;
        $this->authorizationResponseMapper = $authorizationResponseMapper;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function preAuthorizePayment(int $idSalesOrder): AuthorizationResponseTransfer
    {
        $paymentPayoneEntity = $this->getPaymentEntity($idSalesOrder);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentPayoneEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentPayoneEntity);
        $responseContainer = $this->baseAuthorizeSender->performAuthorizationRequest($paymentPayoneEntity, $requestContainer);

        $responseTransfer = $this->authorizationResponseMapper->getAuthorizationResponseTransfer($responseContainer);

        return $responseTransfer;
    }
}
