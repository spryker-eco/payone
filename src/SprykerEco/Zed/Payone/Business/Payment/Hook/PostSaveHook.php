<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Hook;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PayoneApiLogTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PostSaveHook implements PostSaveHookInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     */
    public function __construct(PayoneRepositoryInterface $payoneRepository)
    {
        $this->payoneRepository = $payoneRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer
    {
        $apiLogTransfer = $this->payoneRepository->createLastApiLogsByOrderId($quoteTransfer->getPayment()->getPayone()->getFkSalesOrder());

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
            $checkoutResponse->addError($this->createCheckoutErrorTransfer($apiLogTransfer, $errorCode));
            $checkoutResponse->setIsSuccess(false);
        }

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
}
