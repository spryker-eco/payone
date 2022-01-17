<?php
// phpcs:disable SprykerStrict.TypeHints.ReturnTypeHint.MissingNativeTypeHint

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone\Dependency;

interface TransactionStatusUpdateInterface
{
    /**
     * @return int
     */
    public function getAccessid(): int;

    /**
     * @return int
     */
    public function getAid(): int;

    /**
     * @return float
     */
    public function getBalance(): float;

    /**
     * @return string
     */
    public function getClearingtype(): string;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @return int
     */
    public function getCustomerid(): int;

    /**
     * @return string
     */
    public function getFailedcause(): string;

    /**
     * @return string
     */
    public function getInvoiceDate(): string;

    /**
     * @return string
     */
    public function getInvoiceDeliverydate(): string;

    /**
     * @return string
     */
    public function getInvoiceDeliveryenddate(): string;

    /**
     * @return string
     */
    public function getInvoiceGrossamount(): string;

    /**
     * @return string
     */
    public function getInvoiceid(): string;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return string
     */
    public function getMode(): string;

    /**
     * @return string
     */
    public function getParam(): string;

    /**
     * @return int
     */
    public function getPortalid(): int;

    /**
     * @return int
     */
    public function getProductid(): int;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @return float
     */
    public function getReceivable(): float;

    /**
     * @return string
     */
    public function getReference(): string;

    /**
     * @return string
     */
    public function getReminderlevel();

    /**
     * @return string
     */
    public function getSequencenumber(): string;

    /**
     * @return string
     */
    public function getTxaction(): string;

    /**
     * @return int
     */
    public function getTxid(): int;

    /**
     * @return int
     */
    public function getTxtime(): int;

    /**
     * @return int
     */
    public function getUserid(): int;

    /**
     * @return string
     */
    public function getClearingBankaccount(): string;

    /**
     * @return string
     */
    public function getClearingBankaccountholder(): string;

    /**
     * @return string
     */
    public function getClearingBankbic(): string;

    /**
     * @return string
     */
    public function getClearingBankcity(): string;

    /**
     * @return string
     */
    public function getClearingBankcode(): string;

    /**
     * @return string
     */
    public function getClearingBankcountry(): string;

    /**
     * @return string
     */
    public function getClearingBankiban(): string;

    /**
     * @return string
     */
    public function getClearingBankname(): string;

    /**
     * @return string
     */
    public function getIban(): string;

    /**
     * @return string
     */
    public function getBic(): string;

    /**
     * @return string
     */
    public function getMandateIdentification(): string;

    /**
     * @return string
     */
    public function getClearingDuedate(): string;

    /**
     * @return string
     */
    public function getClearingAmount(): string;

    /**
     * @return string
     */
    public function getCreditorIdentifier(): string;

    /**
     * @return string
     */
    public function getClearingDate(): string;

    /**
     * @return string
     */
    public function getClearingInstructionnote(): string;

    /**
     * @return string
     */
    public function getClearingLegalnote(): string;

    /**
     * @return string
     */
    public function getClearingReference(): string;
}
