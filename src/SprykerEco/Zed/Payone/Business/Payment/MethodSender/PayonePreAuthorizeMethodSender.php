<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapper;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayonePreAuthorizeMethodSender extends AbstractPayoneMethodSender implements PayonePreAuthorizeMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface
     */
    protected $baseAuthorizeSender;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReader $paymentMapperReader,
        PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
        $this->baseAuthorizeSender = $baseAuthorizeSender;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function preAuthorizePayment($idSalesOrder): AuthorizationResponseTransfer
    {
        $paymentEntity = $this->getPaymentEntity($idSalesOrder);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity);
        $responseContainer = $this->baseAuthorizeSender->performAuthorizationRequest($paymentEntity, $requestContainer);

        $responseMapper = new AuthorizationResponseMapper();
        $responseTransfer = $responseMapper->getAuthorizationResponseTransfer($responseContainer);

        return $responseTransfer;
    }
}
