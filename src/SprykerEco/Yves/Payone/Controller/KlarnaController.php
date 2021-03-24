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
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTokenAction(Request $request): JsonResponse
    {
        $payMethod = $request->request->get('pay_method');
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $klarnaStartSessionRequestTransfer = new PayoneKlarnaStartSessionRequestTransfer();
        $klarnaStartSessionRequestTransfer->setQuote($quoteTransfer);
        $klarnaStartSessionRequestTransfer->setPayMethod($payMethod);

        $payoneKlarnaSessionResponseTransfer = $this->getClient()->startKlarnaSessionRequest($klarnaStartSessionRequestTransfer);

        return new JsonResponse([
            'is_valid' => $payoneKlarnaSessionResponseTransfer->getIsValid(),
            'error_message' => $payoneKlarnaSessionResponseTransfer->getError(),
            'client_token' => $payoneKlarnaSessionResponseTransfer->getToken(),
        ]);
    }
}
