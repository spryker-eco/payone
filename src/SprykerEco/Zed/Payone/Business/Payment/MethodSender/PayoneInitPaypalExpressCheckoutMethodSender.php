<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
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

class PayoneInitPaypalExpressCheckoutMethodSender implements PayoneInitPaypalExpressCheckoutMethodSenderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager
     */
    protected $paymentMapperManager;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface
     */
    protected $genericRequestMethodSender;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneGenericRequestMethodSenderInterface $genericRequestMethodSender
     */
    public function __construct(
        PaymentMapperManager $paymentMapperManager,
        PayoneGenericRequestMethodSenderInterface $genericRequestMethodSender
    ) {
        $this->paymentMapperManager = $paymentMapperManager;
        $this->genericRequestMethodSender = $genericRequestMethodSender;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
    {
        /** @var \SprykerEco\Zed\Payone\Business\Payment\GenericPaymentMethodMapperInterface $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperManager->getRegisteredPaymentMethodMapper(
            PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT
        );
        $baseGenericPaymentContainer = $paymentMethodMapper->createBaseGenericPaymentContainer();
        $baseGenericPaymentContainer->getPaydata()->setAction(PayoneApiConstants::PAYONE_EXPRESS_CHECKOUT_SET_ACTION);
        $requestContainer = $paymentMethodMapper->mapRequestTransferToGenericPayment(
            $baseGenericPaymentContainer,
            $requestTransfer
        );
        $responseTransfer = $this->genericRequestMethodSender->performGenericRequest($requestContainer);

        return $responseTransfer;
    }
}
