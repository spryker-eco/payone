<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone\Dependency;

interface TransactionStatusUpdateInterface
{
    /**
     * @return int|null
     */
    public function getAccessid(): ?int;

    /**
     * @return int|null
     */
    public function getAid(): ?int;

    /**
     * @return float|null
     */
    public function getBalance(): ?float;

    /**
     * @return string|null
     */
    public function getClearingtype(): ?string;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @return int|null
     */
    public function getCustomerid(): ?int;

    /**
     * @return string|null
     */
    public function getFailedcause(): ?string;

    /**
     * @return string|null
     */
    public function getInvoiceDate(): ?string;

    /**
     * @return string|null
     */
    public function getInvoiceDeliverydate(): ?string;

    /**
     * @return string|null
     */
    public function getInvoiceDeliveryenddate(): ?string;

    /**
     * @return string|null
     */
    public function getInvoiceGrossamount(): ?string;

    /**
     * @return string|null
     */
    public function getInvoiceid(): ?string;

    /**
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * @return string|null
     */
    public function getMode(): ?string;

    /**
     * @return string|null
     */
    public function getParam(): ?string;

    /**
     * @return int|null
     */
    public function getPortalid(): ?int;

    /**
     * @return int|null
     */
    public function getProductid(): ?int;

    /**
     * @return float|null
     */
    public function getPrice(): ?float;

    /**
     * @return float|null
     */
    public function getReceivable(): ?float;

    /**
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * @return string|null
     */
    public function getReminderlevel(): ?string;

    /**
     * @return string|null
     */
    public function getSequencenumber(): ?string;

    /**
     * @return string|null
     */
    public function getTxaction(): ?string;

    /**
     * @return int|null
     */
    public function getTxid(): ?int;

    /**
     * @return int|null
     */
    public function getTxtime(): ?int;

    /**
     * @return int|null
     */
    public function getUserid(): ?int;

    /**
     * @return string|null
     */
    public function getClearingBankaccount(): ?string;

    /**
     * @return string|null
     */
    public function getClearingBankaccountholder(): ?string;

    /**
     * @return string|null
     */
    public function getClearingBankbic(): ?string;

    /**
     * @return string|null
     */
    public function getClearingBankcity(): ?string;

    /**
     * @return string|null
     */
    public function getClearingBankcode(): ?string;

    /**
     * @return string|null
     */
    public function getClearingBankcountry(): ?string;

    /**
     * @return string|null
     */
    public function getClearingBankiban(): ?string;

    /**
     * @return string|null
     */
    public function getClearingBankname(): ?string;

    /**
     * @return string|null
     */
    public function getIban(): ?string;

    /**
     * @return string|null
     */
    public function getBic(): ?string;

    /**
     * @return string|null
     */
    public function getMandateIdentification(): ?string;

    /**
     * @return string|null
     */
    public function getClearingDuedate(): ?string;

    /**
     * @return string|null
     */
    public function getClearingAmount(): ?string;

    /**
     * @return string|null
     */
    public function getCreditorIdentifier(): ?string;

    /**
     * @return string|null
     */
    public function getClearingDate(): ?string;

    /**
     * @return string|null
     */
    public function getClearingInstructionnote(): ?string;

    /**
     * @return string|null
     */
    public function getClearingLegalnote(): ?string;

    /**
     * @return string|null
     */
    public function getClearingReference(): ?string;

    /**
     * @return array
     */
    public function toArray(): array;
}
