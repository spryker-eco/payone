<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\KlarnaGenericPaymentResponseContainer;
use SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapper;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper;

class PayoneKlarnaStartSessionHandler
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
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapper
     */
    protected $productsMappaer;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapper
     */
    protected $shipmentMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapper
     */
    protected $discountMapper;

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
     * @param \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface $urlHmacGenerator
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapper $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapper $productsMappaer
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapper $shipmentMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapper $discountMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        HmacGeneratorInterface $urlHmacGenerator,
        StandartParameterMapper $standartParameterMapper,
        ProductsMapper $productsMappaer,
        ShipmentMapper $shipmentMapper,
        DiscountMapper $discountMapper,
        PaymentMapperManager $paymentMapperManager,
        PayoneStandardParameterTransfer $standardParameter
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->productsMappaer = $productsMappaer;
        $this->shipmentMapper = $shipmentMapper;
        $this->discountMapper = $discountMapper;
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
        $this->productsMappaer->prepareProductItems($payoneKlarnaStartSessionRequestTransfer->getQuote(), $klarnaGenericPaymentContainer);
        $this->shipmentMapper->prepareShipment($payoneKlarnaStartSessionRequestTransfer->getQuote(), $klarnaGenericPaymentContainer);
        $this->discountMapper->prepareDiscount($payoneKlarnaStartSessionRequestTransfer->getQuote(), $klarnaGenericPaymentContainer);
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
