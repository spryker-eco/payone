<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use Generated\Shared\Transfer\BaseResponseTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;

class RefundResponseMapper implements RefundResponseMapperInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer
     */
    public function getRefundResponseTransfer(RefundResponseContainer $responseContainer)
    {
        $result = new RefundResponseTransfer();
        $baseResponse = new BaseResponseTransfer();

        // Fill base response transfer
        $baseResponse->setErrorCode($responseContainer->getErrorcode());
        $baseResponse->setErrorMessage($responseContainer->getErrormessage());
        $baseResponse->setCustomerMessage($responseContainer->getCustomermessage());
        $baseResponse->setStatus($responseContainer->getStatus());
        $baseResponse->setRawResponse($responseContainer->getRawResponse());

        // Set plain attributes
        $result->setTxid($responseContainer->getTxid());
        $result->setProtectResultAvs($responseContainer->getProtectResultAvs());

        // Set aggregated transfers
        $result->setBaseResponse($baseResponse);

        return $result;
    }
}
