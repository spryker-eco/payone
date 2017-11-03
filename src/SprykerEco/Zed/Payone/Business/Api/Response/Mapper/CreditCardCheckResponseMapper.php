<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use Generated\Shared\Transfer\BaseResponseTransfer;
use Generated\Shared\Transfer\CreditCardCheckResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;

class CreditCardCheckResponseMapper implements CreditCardCheckResponseMapperInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function getCreditCardCheckResponseTransfer(CreditCardCheckResponseContainer $responseContainer)
    {
        $result = new CreditCardCheckResponseTransfer();
        $baseResponse = new BaseResponseTransfer();

        // Fill base response transfer
        $baseResponse->setErrorCode($responseContainer->getErrorcode());
        $baseResponse->setErrorMessage($responseContainer->getErrormessage());
        $baseResponse->setCustomerMessage($responseContainer->getCustomermessage());
        $baseResponse->setStatus($responseContainer->getStatus());
        $baseResponse->setRawResponse($responseContainer->getRawResponse());

        // Set plain attributes
        $result->setPseudoCardPan($responseContainer->getPseudocardpan());
        $result->setTruncatedCardPan($responseContainer->getTruncatedcardpan());

        // Set aggregated transfers
        $result->setBaseResponse($baseResponse);

        return $result;
    }
}
