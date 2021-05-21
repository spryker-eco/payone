<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Client\Payone\PayoneClientInterface getClient()
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class KlarnaController extends AbstractController
{
    protected const REQUEST_PARAMETER_PAY_METHOD = 'pay_method';
    protected const RESPONSE_PARAMETER_IS_VALID = 'is_valid';
    protected const RESPONSE_PARAMETER_ERROR_MESSAGE = 'error_message';
    protected const RESPONSE_PARAMETER_CLIENT_TOKEN = 'client_token';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTokenAction(Request $request): JsonResponse
    {
        $payoneKlarnaStartSessionRequestTransfer = $this->createPayoneKlarnaStartSessionRequestTransfer($request);
        $payoneKlarnaStartSessionResponseTransfer = $this->getClient()->sendKlarnaStartSessionRequest($payoneKlarnaStartSessionRequestTransfer);

        return $this->jsonResponse([
            self::RESPONSE_PARAMETER_IS_VALID => $payoneKlarnaStartSessionResponseTransfer->getIsSuccessful(),
            self::RESPONSE_PARAMETER_ERROR_MESSAGE => $payoneKlarnaStartSessionResponseTransfer->getErrorMessage(),
            self::RESPONSE_PARAMETER_CLIENT_TOKEN => $payoneKlarnaStartSessionResponseTransfer->getToken(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer
     */
    protected function createPayoneKlarnaStartSessionRequestTransfer(Request $request): PayoneKlarnaStartSessionRequestTransfer
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payMethod = $request->request->get(self::REQUEST_PARAMETER_PAY_METHOD);

        $payoneKlarnaStartSessionRequestTransfer = new PayoneKlarnaStartSessionRequestTransfer();
        $payoneKlarnaStartSessionRequestTransfer->setQuote($quoteTransfer);
        $payoneKlarnaStartSessionRequestTransfer->setPayMethod($payMethod);

        return $payoneKlarnaStartSessionRequestTransfer;
    }
}
