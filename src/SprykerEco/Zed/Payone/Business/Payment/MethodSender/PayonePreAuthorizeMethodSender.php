<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapper;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayonePreAuthorizeMethodSender extends AbstractPayoneMethodSender implements PayonePreAuthorizeMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface
     */
    protected $baseAuthorizeSender;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperManager $paymentMapperManager,
        PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
    ) {
        parent::__construct($queryContainer, $paymentMapperManager);
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
