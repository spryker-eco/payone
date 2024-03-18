<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Hook;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\KlarnaPaymentMapper;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\RequestSender\PayoneBaseAuthorizeSenderInterface;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PostSaveHook implements PostSaveHookInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface
     */
    protected $payoneRequestProductDataMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\RequestSender\PayoneBaseAuthorizeSenderInterface
     */
    protected $baseAuthorizeSender;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\RequestSender\PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
     */
    public function __construct(
        PayoneRepositoryInterface $payoneRepository,
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneRequestProductDataMapperInterface $payoneRequestProductDataMapper,
        PayoneBaseAuthorizeSenderInterface $baseAuthorizeSender
    ) {
        $this->payoneRepository = $payoneRepository;
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
        $apiLogTransfer = $this->payoneRepository->findLastApiLogByOrderId($quoteTransfer->getPayment()->getPayone()->getFkSalesOrder());

        if (!$apiLogTransfer) {
            return $checkoutResponse;
        }
        $redirectUrl = $apiLogTransfer->getRedirectUrl();

        if ($redirectUrl !== null) {
            $checkoutResponse->setIsExternalRedirect(true);
            $checkoutResponse->setRedirectUrl($redirectUrl);
        }

        $errorCode = $apiLogTransfer->getErrorCode();

        if ($errorCode) {
            $checkoutResponse->addError($this->createCheckoutErrorTransfer($apiLogTransfer->getErrorMessageUser(), $errorCode));
            $checkoutResponse->setIsSuccess(false);
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
        $paymentEntity = $this->payoneRepository->createPaymentPayoneQueryByOrderId($checkoutResponse->getSaveOrder()->getIdSalesOrder())->findOne();
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper($paymentEntity->getPaymentMethod());
        $requestContainer = $this->getPostSaveHookRequestContainer($paymentMethodMapper, $paymentEntity, $quoteTransfer);

        if ($paymentEntity->getPaymentMethod() === PayoneApiConstants::PAYMENT_METHOD_KLARNA) {
            $requestContainer = $this->payoneRequestProductDataMapper->mapProductData($quoteTransfer, $requestContainer);
        }

        $rawResponse = $this->payoneRepository->getPreauthorizedPaymentByReference($requestContainer->getReference());
        $responseContainer = $rawResponse === [] ? $this->baseAuthorizeSender->performAuthorizationRequest($paymentEntity, $requestContainer) : new AuthorizationResponseContainer($rawResponse);

        if ($responseContainer->getErrorcode()) {
            $checkoutErrorTransfer = $this->createCheckoutErrorTransfer($responseContainer->getCustomermessage(), $responseContainer->getErrorcode());
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
     * @param string $errorMessage
     * @param string $errorCode
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(string $errorMessage, string $errorCode): CheckoutErrorTransfer
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();

        $checkoutErrorTransfer->setMessage($errorMessage);
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
            if ($paymentMethodMapper instanceof KlarnaPaymentMapper) {
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
