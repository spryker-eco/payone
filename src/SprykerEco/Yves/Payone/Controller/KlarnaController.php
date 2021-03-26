<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Client\Payone\PayoneClientInterface getClient()
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class KlarnaController extends AbstractController
{
    protected const PAY_METHOD_REQUEST_PARAMETER = 'pay_method';
    protected const IS_VALID_RESPONSE_PARAMETER = 'is_valid';
    protected const ERROR_MESSAGE_RESPONSE_PARAMETER = 'error_message';
    protected const CLIENT_TOKEN_RESPONSE_PARAMETER = 'client_token';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTokenAction(Request $request): JsonResponse
    {
        $klarnaStartSessionRequestTransfer = $this->getFactory()->createPayoneKlarnaStartSessionRequest();
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $klarnaStartSessionRequestTransfer->setQuote($quoteTransfer);
        $payMethod = $request->request->get(self::PAY_METHOD_REQUEST_PARAMETER);
        $klarnaStartSessionRequestTransfer->setPayMethod($payMethod);

        $payoneKlarnaSessionResponseTransfer = $this->getClient()->startKlarnaSessionRequest($klarnaStartSessionRequestTransfer);

        return $this->jsonResponse([
            self::IS_VALID_RESPONSE_PARAMETER => $payoneKlarnaSessionResponseTransfer->getIsValid(),
            self::ERROR_MESSAGE_RESPONSE_PARAMETER => $payoneKlarnaSessionResponseTransfer->getError(),
            self::CLIENT_TOKEN_RESPONSE_PARAMETER => $payoneKlarnaSessionResponseTransfer->getToken(),
        ]);
    }
}
