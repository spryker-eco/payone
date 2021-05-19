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
use SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapperInterface;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

class PayoneKlarnaStartSessionMethodSender implements PayoneKlarnaStartSessionMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface
     */
    protected $payoneRequestProductDataMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapperInterface
     */
    protected $klarnaPaymentMapper;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    protected $sequenceNumberProvider;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface
     */
    protected $urlHmacGenerator;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapperInterface $klarnaPaymentMapper
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     * @param \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface $urlHmacGenerator
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        StandartParameterMapperInterface $standartParameterMapper,
        PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper,
        KlarnaPaymentMapperInterface $klarnaPaymentMapper,
        PayoneStandardParameterTransfer $standardParameter,
        SequenceNumberProviderInterface $sequenceNumberProvider,
        HmacGeneratorInterface $urlHmacGenerator
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->payoneRequestProductDataMapper = $payoneRequestProductDataMapper;
        $this->klarnaPaymentMapper = $klarnaPaymentMapper;
        $this->standardParameter = $standardParameter;
        $this->sequenceNumberProvider = $sequenceNumberProvider;
        $this->urlHmacGenerator = $urlHmacGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function sendKlarnaStartSessionRequest(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ): PayoneKlarnaStartSessionResponseTransfer {
        $this->klarnaPaymentMapper->setStandardParameter($this->standardParameter);
        $this->klarnaPaymentMapper->setSequenceNumberProvider($this->sequenceNumberProvider);
        $this->klarnaPaymentMapper->setUrlHmacGenerator($this->urlHmacGenerator);

        $klarnaGenericPaymentContainer = $this->klarnaPaymentMapper->mapPaymentToKlarnaGenericPaymentContainer($payoneKlarnaStartSessionRequestTransfer);

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
