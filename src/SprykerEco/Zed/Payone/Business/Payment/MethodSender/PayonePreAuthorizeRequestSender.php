<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayonePreAuthorizeRequestSender extends AbstractPayoneRequestSender implements PayonePreAuthorizeRequestSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface
     */
    protected $baseAuthorizeSender;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface
     */
    protected $authorizationResponseMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapperInterface $authorizationResponseMapper
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender,
        AuthorizationResponseMapperInterface $authorizationResponseMapper
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
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
        $paymentEntity = $this->getPaymentEntity($idSalesOrder);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity);
        $responseContainer = $this->baseAuthorizeSender->performAuthorizationRequest($paymentEntity, $requestContainer);

        $responseTransfer = $this->authorizationResponseMapper->getAuthorizationResponseTransfer($responseContainer);

        return $responseTransfer;
    }
}
