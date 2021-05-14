<?php

namespace SprykerEco\Zed\Payone\Business\Payment\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PostSaveHook implements PostSaveHookInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    public function __construct(PayoneQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): CheckoutResponseTransfer
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
}
