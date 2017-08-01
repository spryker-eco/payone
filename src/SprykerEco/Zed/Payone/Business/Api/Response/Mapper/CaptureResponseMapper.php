<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Mapper;

use Generated\Shared\Transfer\BaseResponseTransfer;
use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\ClearingTransfer;
use Generated\Shared\Transfer\CreditorTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;

class CaptureResponseMapper implements CaptureResponseMapperInterface
{

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\CaptureResponseTransfer
     */
    public function getCaptureResponseTransfer(CaptureResponseContainer $responseContainer)
    {
        $result = new CaptureResponseTransfer();
        $clearing = new ClearingTransfer();
        $creditor = new CreditorTransfer();
        $baseResponse = new BaseResponseTransfer();

        // Fill clearing transfer
        $clearing->setBankAccountHolder($responseContainer->getClearingBankaccountholder());
        $clearing->setBankCountry($responseContainer->getClearingBankcountry());
        $clearing->setBankAccount($responseContainer->getClearingBankaccount());
        $clearing->setBankCode($responseContainer->getClearingBankcode());
        $clearing->setBankIban($responseContainer->getClearingBankiban());
        $clearing->setBankBic($responseContainer->getClearingBankbic());
        $clearing->setBankCity($responseContainer->getClearingBankcity());
        $clearing->setBankName($responseContainer->getClearingBankname());
        $clearing->setLegalNote($responseContainer->getClearingLegalnote());
        $clearing->setDate($responseContainer->getClearingDate());
        $clearing->setDueDate($responseContainer->getClearingDuedate());
        $clearing->setReference($responseContainer->getClearingReference());
        $clearing->setInstructionNote($responseContainer->getClearingInstructionnote());
        $clearing->setAmount($responseContainer->getClearingAmount());

        // Fill creditor transfer
        $creditor->setIdentifier($responseContainer->getCreditorIdentifier());
        $creditor->setName($responseContainer->getCreditorName());
        $creditor->setStreet($responseContainer->getCreditorStreet());
        $creditor->setZip($responseContainer->getCreditorZip());
        $creditor->setCity($responseContainer->getCreditorCity());
        $creditor->setCountry($responseContainer->getCreditorCountry());
        $creditor->setEmail($responseContainer->getCreditorEmail());

        // Fill base response transfer
        $baseResponse->setErrorCode($responseContainer->getErrorcode());
        $baseResponse->setErrorMessage($responseContainer->getErrormessage());
        $baseResponse->setCustomerMessage($responseContainer->getCustomermessage());
        $baseResponse->setStatus($responseContainer->getStatus());
        $baseResponse->setRawResponse($responseContainer->getRawResponse());

        // Set plain attributes
        $result->setTxid($responseContainer->getTxid());
        $result->setSettleAccount($responseContainer->getSettleaccount());
        $result->setMandateIdentification($responseContainer->getMandateIdentification());

        // Set aggregated transfers
        $result->setClearing($clearing);
        $result->setCreditor($creditor);
        $result->setBaseResponse($baseResponse);

        return $result;
    }

}
