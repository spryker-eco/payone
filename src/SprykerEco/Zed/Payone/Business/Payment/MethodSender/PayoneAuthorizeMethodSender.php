<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneAuthorizeMethodSender extends AbstractPayoneMethodSender implements PayoneAuthorizeMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface
     */
    protected $payoneRequestProductDataMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface
     */
    protected $baseAuthorizeSender;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader $paymentMapperReader
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReader $paymentMapperReader,
        PayoneStandardParameterTransfer $standardParameter,
        PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper,
        PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
    ) {
        parent::__construct($queryContainer, $paymentMapperReader);
        $this->payoneRequestProductDataMapper = $payoneRequestProductDataMapper;
        $this->baseAuthorizeSender = $baseAuthorizeSender;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorizePayment(OrderTransfer $orderTransfer): AuthorizationResponseTransfer
    {
        $paymentEntity = $this->getPaymentEntity($orderTransfer->getIdSalesOrder());
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToAuthorization($paymentEntity, $orderTransfer);
        $requestContainer = $this->payoneRequestProductDataMapper->mapData($orderTransfer, $requestContainer);
        $responseContainer = $this->baseAuthorizeSender->performAuthorizationRequest($paymentEntity, $requestContainer);

        $responseMapper = new AuthorizationResponseMapper();
        $responseTransfer = $responseMapper->getAuthorizationResponseTransfer($responseContainer);

        return $responseTransfer;
    }
}
