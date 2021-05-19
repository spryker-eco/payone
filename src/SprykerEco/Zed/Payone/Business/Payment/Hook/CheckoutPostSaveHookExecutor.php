<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Hook;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class CheckoutPostSaveHookExecutor implements CheckoutPostSaveHookExecutorInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader
     */
    protected $paymentMapperReader;

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
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReader $paymentMapperReader,
        PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper,
        PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
    ) {
        $this->queryContainer = $queryContainer;
        $this->paymentMapperReader = $paymentMapperReader;
        $this->payoneRequestProductDataMapper = $payoneRequestProductDataMapper;
        $this->baseAuthorizeSender = $baseAuthorizeSender;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function executeCheckoutPostSaveHook(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponse
    ): CheckoutResponseTransfer {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== PayoneConfig::PROVIDER_NAME) {
            return $checkoutResponse;
        }

        $idSalesOrder = $checkoutResponse->getSaveOrder()->getIdSalesOrder();
        $paymentEntity = $this->queryContainer->createPaymentById($idSalesOrder)->findOne();

        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper($paymentEntity->getPaymentMethod());
        $requestContainer = $this->getPostSaveHookRequestContainer($paymentMethodMapper, $paymentEntity, $quoteTransfer);

        if ($paymentEntity->getPaymentMethod() === PayoneApiConstants::PAYMENT_METHOD_KLARNA) {
            $this->payoneRequestProductDataMapper->mapData($quoteTransfer, $requestContainer);
        }

        $responseContainer = $this->baseAuthorizeSender->performAuthorizationRequest($paymentEntity, $requestContainer);

        if ($responseContainer->getErrorcode()) {
            $checkoutErrorTransfer = new CheckoutErrorTransfer();
            $checkoutErrorTransfer->setMessage($responseContainer->getCustomermessage());
            $checkoutErrorTransfer->setErrorCode($responseContainer->getErrorcode());
            $checkoutResponse->addError($checkoutErrorTransfer);
            $checkoutResponse->setIsSuccess(false);

            return $checkoutResponse;
        }

        if (!$responseContainer->getRedirecturl()) {
            return $checkoutResponse;
        }

        $checkoutResponse->setIsExternalRedirect(true);
        $checkoutResponse->setRedirectUrl($responseContainer->getRedirecturl());

        return $checkoutResponse;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface $paymentMethodMapper
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    protected function getPostSaveHookRequestContainer(
        PaymentMethodMapperInterface $paymentMethodMapper,
        SpyPaymentPayone $paymentEntity,
        QuoteTransfer $quoteTransfer
    ): AuthorizationContainerInterface {
        if (method_exists($paymentMethodMapper, 'mapPaymentToPreAuthorization')) {
            if ($paymentEntity->getPaymentMethod() === PayoneApiConstants::PAYMENT_METHOD_KLARNA) {

                return $paymentMethodMapper->mapKlarnaPaymentToPreAuthorization($paymentEntity, $quoteTransfer);
            }

            return $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity);
        }

        $orderTransfer = (new OrderTransfer())
            ->setTotals(
                (new TotalsTransfer())
                    ->setGrandTotal($quoteTransfer->getTotals()->getGrandTotal())
            );

        return $paymentMethodMapper->mapPaymentToAuthorization($paymentEntity, $orderTransfer);
    }
}
