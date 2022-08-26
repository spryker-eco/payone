<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\GenericPaymentResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;

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
    protected $standardParameterMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standardParameterMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standardParameterMapper
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->standardParameterMapper = $standardParameterMapper;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer $requestContainer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function performGenericRequest(GenericPaymentContainer $requestContainer): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
    {
        $this->standardParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new GenericPaymentResponseContainer($rawResponse);

        return $this->mapToPayonePaypalExpressCheckoutGenericPaymentResponseTransfer($responseContainer, $rawResponse);
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\GenericPaymentResponseContainer $responseContainer
     * @param array $rawResponse
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    protected function mapToPayonePaypalExpressCheckoutGenericPaymentResponseTransfer(
        GenericPaymentResponseContainer $responseContainer,
        array $rawResponse
    ): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer {
        $responseTransfer = new PayonePaypalExpressCheckoutGenericPaymentResponseTransfer();

        $responseTransfer->setRedirectUrl($responseContainer->getRedirectUrl());
        $responseTransfer->setWorkOrderId($responseContainer->getWorkOrderId());
        $responseTransfer->setRawResponse(json_encode($rawResponse) ?: '');
        $responseTransfer->setStatus($responseContainer->getStatus());
        $responseTransfer->setCustomerMessage($responseContainer->getCustomerMessage());
        $responseTransfer->setErrorMessage($responseContainer->getErrormessage());
        if ($responseContainer->getErrorcode()) {
            $responseTransfer->setErrorCode((int)$responseContainer->getErrorcode());
        }
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
