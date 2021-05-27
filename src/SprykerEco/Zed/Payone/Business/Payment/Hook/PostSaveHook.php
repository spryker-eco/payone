<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Hook;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneApiLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PostSaveHook implements PostSaveHookInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
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
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\MethodSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PaymentMapperReaderInterface $paymentMapperReader,
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
    public function executePostSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== PayoneConfig::PROVIDER_NAME) {
            return $checkoutResponse;
        }

        $checkoutResponse = $this->checkApiLogRedirectAndError($quoteTransfer, $checkoutResponse);

        return $this->checkApiAuthorizationRedirectAndError($quoteTransfer, $checkoutResponse);
    }

    /**
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\Payment\Hook\PostSaveHook::executePostSaveHook()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== PayoneConfig::PROVIDER_NAME) {
            return $checkoutResponse;
        }

        return $this->checkApiLogRedirectAndError($quoteTransfer, $checkoutResponse);
    }

    /**
     * @deprecated Use {@link \SprykerEco\Zed\Payone\Business\Payment\Hook\PostSaveHook::executePostSaveHook()} instead.
     *
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

        return $this->checkApiAuthorizationRedirectAndError($quoteTransfer, $checkoutResponse);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function checkApiLogRedirectAndError(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer
    {
        $apiLogsQuery = $this->queryContainer->createLastApiLogsByOrderId($quoteTransfer->getPayment()->getPayone()->getFkSalesOrder());
        $apiLog = $apiLogsQuery->findOne();

        if ($apiLog) {
            $redirectUrl = $apiLog->getRedirectUrl();

            if ($redirectUrl !== null) {
                $checkoutResponse->setIsExternalRedirect(true);
                $checkoutResponse->setRedirectUrl($redirectUrl);
            }

            $errorCode = $apiLog->getErrorCode();

            if ($errorCode) {
                $error = new CheckoutErrorTransfer();
                $error->setMessage($apiLog->getErrorMessageUser());
                $error->setErrorCode($errorCode);
                $checkoutResponse->addError($error);
                $checkoutResponse->setIsSuccess(false);
            }
        }

        return $checkoutResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function checkApiAuthorizationRedirectAndError(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer
    {
        $paymentEntity = $this->queryContainer->createPaymentById($checkoutResponse->getSaveOrder()->getIdSalesOrder())->findOne();
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper($paymentEntity->getPaymentMethod());
        $requestContainer = $this->getPostSaveHookRequestContainer($paymentMethodMapper, $paymentEntity, $quoteTransfer);

        if ($paymentEntity->getPaymentMethod() === PayoneApiConstants::PAYMENT_METHOD_KLARNA) {
            $requestContainer = $this->payoneRequestProductDataMapper->mapProductData($quoteTransfer, $requestContainer);
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
     * @param \Generated\Shared\Transfer\PayoneApiLogTransfer $apiLogTransfer
     * @param string $errorCode
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(PayoneApiLogTransfer $apiLogTransfer, string $errorCode): CheckoutErrorTransfer
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();

        $checkoutErrorTransfer->setMessage($apiLogTransfer->getErrorMessageUser());
        $checkoutErrorTransfer->setErrorCode($errorCode);

        return $checkoutErrorTransfer;
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
