<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\GenericPaymentResponseContainer;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayoneGenericRequestMethodSender implements PayoneGenericRequestMethodSenderInterface
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
    protected $standartParameterMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer $requestContainer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function performGenericRequest(GenericPaymentContainer $requestContainer): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
    {
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new GenericPaymentResponseContainer($rawResponse);
        $responseTransfer = new PayonePaypalExpressCheckoutGenericPaymentResponseTransfer();
        $responseTransfer->setRedirectUrl($responseContainer->getRedirectUrl());
        $responseTransfer->setWorkOrderId($responseContainer->getWorkOrderId());
        $responseTransfer->setRawResponse(json_encode($rawResponse));
        $responseTransfer->setStatus($responseContainer->getStatus());
        $responseTransfer->setCustomerMessage($responseContainer->getCustomermessage());
        $responseTransfer->setErrorMessage($responseContainer->getErrormessage());
        $responseTransfer->setErrorCode($responseContainer->getErrorcode());
        $responseTransfer->setEmail($responseContainer->getEmail());
        $responseTransfer->setShippingFirstName($responseContainer->getShippingFirstname());
        $responseTransfer->setShippingLastName($responseContainer->getShippingLastname());
        $responseTransfer->setShippingCompany($responseContainer->getShippingCompany());
        $responseTransfer->setShippingCountry($responseContainer->getShippingCountry());
        $responseTransfer->setShippingState($responseContainer->getShippingState());
        $responseTransfer->setShippingStreet($responseContainer->getShippingStreet());
        $responseTransfer->setShippingAddressAdition($responseContainer->getShippingAddressaddition());
        $responseTransfer->setShippingCity($responseContainer->getShippingCity());
        $responseTransfer->setShippingZip($responseContainer->getShippingZip());

        return $responseTransfer;
    }
}
