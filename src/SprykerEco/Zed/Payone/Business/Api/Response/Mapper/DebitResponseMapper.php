<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use Generated\Shared\Transfer\BaseResponseTransfer;
use Generated\Shared\Transfer\DebitResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;

class DebitResponseMapper implements DebitResponseMapperInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\DebitResponseTransfer
     */
    public function getDebitResponseTransfer(DebitResponseContainer $responseContainer): DebitResponseTransfer
    {
        $result = new DebitResponseTransfer();
        $baseResponse = new BaseResponseTransfer();

        // Fill base response transfer
        $baseResponse->setErrorCode($responseContainer->getErrorcode());
        $baseResponse->setErrorMessage($responseContainer->getErrormessage());
        $baseResponse->setCustomerMessage($responseContainer->getCustomerMessage());
        $baseResponse->setStatus($responseContainer->getStatus());
        $baseResponse->setRawResponse($responseContainer->getRawResponse());

        // Set plain attributes
        $result->setTxid($responseContainer->getTxid());
        $result->setSettleAccount($responseContainer->getSettleaccount());

        // Set aggregated transfers
        $result->setBaseResponse($baseResponse);

        return $result;
    }
}
