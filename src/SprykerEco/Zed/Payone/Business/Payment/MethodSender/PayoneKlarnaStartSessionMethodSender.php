<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\KlarnaGenericPaymentResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;

class PayoneKlarnaStartSessionMethodSender extends AbstractPayoneMethodSender implements PayoneKlarnaStartSessionMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface
     */
    protected $payoneRequestProductDataMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager
     */
    protected $paymentMapperManager;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        StandartParameterMapper $standartParameterMapper,
        PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper,
        PaymentMapperManager $paymentMapperManager,
        PayoneStandardParameterTransfer $standardParameter
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->payoneRequestProductDataMapper = $payoneRequestProductDataMapper;
        $this->paymentMapperManager = $paymentMapperManager;
        $this->standardParameter = $standardParameter;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function sendKlarnaStartSessionRequest(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ): PayoneKlarnaStartSessionResponseTransfer {
        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapper $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperManager->getRegisteredPaymentMethodMapper(
            PayoneApiConstants::PAYMENT_METHOD_KLARNA
        );

        $klarnaGenericPaymentContainer = $paymentMethodMapper->mapPaymentToKlarnaGenericPaymentContainer($payoneKlarnaStartSessionRequestTransfer);
        $this->standartParameterMapper->setStandardParameter($klarnaGenericPaymentContainer, $this->standardParameter);
        $this->payoneRequestProductDataMapper->mapData($payoneKlarnaStartSessionRequestTransfer->getQuote(), $klarnaGenericPaymentContainer);
        $rawResponse = $this->executionAdapter->sendRequest($klarnaGenericPaymentContainer);

        $klarnaGenericPaymentResponseContainer = new KlarnaGenericPaymentResponseContainer($rawResponse);

        $payoneKlarnaStartSessionResponseTransfer = new PayoneKlarnaStartSessionResponseTransfer();

        if ($klarnaGenericPaymentResponseContainer->getStatus() === PayoneApiConstants::RESPONSE_TYPE_ERROR) {
            $payoneKlarnaStartSessionResponseTransfer->setIsSuccessful(false);
            $payoneKlarnaStartSessionResponseTransfer->setErrorMessage($klarnaGenericPaymentResponseContainer->getErrormessage());

            return $payoneKlarnaStartSessionResponseTransfer;
        }

        $payoneKlarnaStartSessionResponseTransfer->setIsSuccessful(true);
        $payoneKlarnaStartSessionResponseTransfer->setToken($klarnaGenericPaymentResponseContainer->getClientToken());

        return $payoneKlarnaStartSessionResponseTransfer;
    }
}
