<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\TransactionStatus;

use SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface;

class TransactionStatusRequest extends AbstractRequest implements TransactionStatusUpdateInterface
{
    /**
     * @var string Payment portal key as MD5 value
     */
    protected $key;

    /**
     * @var string
     */
    protected $txaction;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var int Payment portal ID
     */
    protected $portalid;

    /**
     * @var int Account ID (subaccount ID)
     */
    protected $aid;

    /**
     * @var string
     */
    protected $clearingtype;

    /**
     * unix timestamp
     *
     * @var int
     */
    protected $txtime;

    /**
     * @var string ISO-4217
     */
    protected $currency;

    /**
     * @var int
     */
    protected $userid;

    /**
     * @var int
     */
    protected $customerid;

    /**
     * @var string
     */
    protected $param;

    /**
     * @var int
     */
    protected $txid;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $sequencenumber;

    /**
     * @var float
     */
    protected $receivable;

    /**
     * @var float
     */
    protected $balance;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $failedcause;

    /**
     * @var int
     */
    protected $productid;

    /**
     * @var int
     */
    protected $accessid;

    /**
     * @var string
     */
    protected $reminderlevel;

    /**
     * @var string
     */
    protected $invoiceid;

    /**
     * @var string
     */
    protected $invoice_grossamount;

    /**
     * @var string
     */
    protected $invoice_date;

    /**
     * @var string
     */
    protected $invoice_deliverydate;

    /**
     * @var string
     */
    protected $invoice_deliveryenddate;

    /**
     * @var string
     */
    protected $clearing_bankaccountholder;

    /**
     * @var string
     */
    protected $clearing_bankcountry;

    /**
     * @var string
     */
    protected $clearing_bankaccount;

    /**
     * @var string
     */
    protected $clearing_bankcode;

    /**
     * @var string
     */
    protected $clearing_bankiban;

    /**
     * @var string
     */
    protected $clearing_bankbic;

    /**
     * @var string
     */
    protected $clearing_bankcity;

    /**
     * @var string
     */
    protected $clearing_bankname;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $bic;

    /**
     * @var string
     */
    protected $mandate_identification;

    /**
     * @var string
     */
    protected $clearing_date;

    /**
     * @var string
     */
    protected $clearing_amount;

    /**
     * @var string
     */
    protected $creditor_identifier;

    /**
     * @var string
     */
    protected $clearing_legalnote;

    /**
     * (YYYYMMDD)
     *
     * @var string
     */
    protected $clearing_duedate;

    /**
     * @var string
     */
    protected $clearing_reference;

    /**
     * @var string
     */
    protected $clearing_instructionnote;

    /**
     * @param int $accessid
     *
     * @return void
     */
    public function setAccessid(int $accessid): void
    {
        $this->accessid = $accessid;
    }

    /**
     * @return int
     */
    public function getAccessid(): int
    {
        return $this->accessid;
    }

    /**
     * @param int $aid
     *
     * @return void
     */
    public function setAid(int $aid): void
    {
        $this->aid = $aid;
    }

    /**
     * @return int
     */
    public function getAid(): int
    {
        return $this->aid;
    }

    /**
     * @param float $balance
     *
     * @return void
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param string $clearingtype
     *
     * @return void
     */
    public function setClearingtype(string $clearingtype): void
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string
     */
    public function getClearingtype(): string
    {
        return $this->clearingtype;
    }

    /**
     * @param string $currency
     *
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param int $customerid
     *
     * @return void
     */
    public function setCustomerid(int $customerid): void
    {
        $this->customerid = $customerid;
    }

    /**
     * @return int
     */
    public function getCustomerid(): int
    {
        return $this->customerid;
    }

    /**
     * @param string $failedcause
     *
     * @return void
     */
    public function setFailedcause(string $failedcause): void
    {
        $this->failedcause = $failedcause;
    }

    /**
     * @return string
     */
    public function getFailedcause(): string
    {
        return $this->failedcause;
    }

    /**
     * @param string $invoiceDate
     *
     * @return void
     */
    public function setInvoiceDate(string $invoiceDate): void
    {
        $this->invoice_date = $invoiceDate;
    }

    /**
     * @return string
     */
    public function getInvoiceDate(): string
    {
        return $this->invoice_date;
    }

    /**
     * @param string $invoiceDeliverydate
     *
     * @return void
     */
    public function setInvoiceDeliverydate(string $invoiceDeliverydate): void
    {
        $this->invoice_deliverydate = $invoiceDeliverydate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliverydate(): string
    {
        return $this->invoice_deliverydate;
    }

    /**
     * @param string $invoiceDeliveryenddate
     *
     * @return void
     */
    public function setInvoiceDeliveryenddate(string $invoiceDeliveryenddate): void
    {
        $this->invoice_deliveryenddate = $invoiceDeliveryenddate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliveryenddate(): string
    {
        return $this->invoice_deliveryenddate;
    }

    /**
     * @param string $invoiceGrossamount
     *
     * @return void
     */
    public function setInvoiceGrossamount(string $invoiceGrossamount): void
    {
        $this->invoice_grossamount = $invoiceGrossamount;
    }

    /**
     * @return string
     */
    public function getInvoiceGrossamount(): string
    {
        return $this->invoice_grossamount;
    }

    /**
     * @param string $invoiceid
     *
     * @return void
     */
    public function setInvoiceid(string $invoiceid): void
    {
        $this->invoiceid = $invoiceid;
    }

    /**
     * @return string
     */
    public function getInvoiceid(): string
    {
        return $this->invoiceid;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $mode
     *
     * @return void
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $param
     *
     * @return void
     */
    public function setParam(string $param): void
    {
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->param;
    }

    /**
     * @param int $portalid
     *
     * @return void
     */
    public function setPortalid(int $portalid): void
    {
        $this->portalid = $portalid;
    }

    /**
     * @return int
     */
    public function getPortalid(): int
    {
        return $this->portalid;
    }

    /**
     * @param int $productid
     *
     * @return void
     */
    public function setProductid(int $productid): void
    {
        $this->productid = $productid;
    }

    /**
     * @return int
     */
    public function getProductid(): int
    {
        return $this->productid;
    }

    /**
     * @param float $receivable
     *
     * @return void
     */
    public function setReceivable(float $receivable): void
    {
        $this->receivable = $receivable;
    }

    /**
     * @return float
     */
    public function getReceivable()
    {
        return $this->receivable;
    }

    /**
     * @param string $reference
     *
     * @return void
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reminderlevel
     *
     * @return void
     */
    public function setReminderlevel(string $reminderlevel): void
    {
        $this->reminderlevel = $reminderlevel;
    }

    /**
     * @return string
     */
    public function getReminderlevel()
    {
        return $this->reminderlevel;
    }

    /**
     * @param string $sequencenumber
     *
     * @return void
     */
    public function setSequencenumber(string $sequencenumber): void
    {
        $this->sequencenumber = $sequencenumber;
    }

    /**
     * @return string
     */
    public function getSequencenumber(): string
    {
        return $this->sequencenumber;
    }

    /**
     * @param string $txaction
     *
     * @return void
     */
    public function setTxaction(string $txaction): void
    {
        $this->txaction = $txaction;
    }

    /**
     * @return string
     */
    public function getTxaction(): string
    {
        return $this->txaction;
    }

    /**
     * @param int $txid
     *
     * @return void
     */
    public function setTxid(int $txid): void
    {
        $this->txid = $txid;
    }

    /**
     * @return int
     */
    public function getTxid(): int
    {
        return $this->txid;
    }

    /**
     * @param int $txtime
     *
     * @return void
     */
    public function setTxtime(int $txtime): void
    {
        $this->txtime = $txtime;
    }

    /**
     * @return int
     */
    public function getTxtime(): int
    {
        return $this->txtime;
    }

    /**
     * @param int $userid
     *
     * @return void
     */
    public function setUserid(int $userid): void
    {
        $this->userid = $userid;
    }

    /**
     * @return int
     */
    public function getUserid(): int
    {
        return $this->userid;
    }

    /**
     * @param string $clearingBankaccount
     *
     * @return void
     */
    public function setClearingBankaccount(string $clearingBankaccount): void
    {
        $this->clearing_bankaccount = $clearingBankaccount;
    }

    /**
     * @return string
     */
    public function getClearingBankaccount(): string
    {
        return $this->clearing_bankaccount;
    }

    /**
     * @param string $clearingBankaccountholder
     *
     * @return void
     */
    public function setClearingBankaccountholder(string $clearingBankaccountholder): void
    {
        $this->clearing_bankaccountholder = $clearingBankaccountholder;
    }

    /**
     * @return string
     */
    public function getClearingBankaccountholder(): string
    {
        return $this->clearing_bankaccountholder;
    }

    /**
     * @param string $clearingBankbic
     *
     * @return void
     */
    public function setClearingBankbic(string $clearingBankbic): void
    {
        $this->clearing_bankbic = $clearingBankbic;
    }

    /**
     * @return string
     */
    public function getClearingBankbic(): string
    {
        return $this->clearing_bankbic;
    }

    /**
     * @param string $clearingBankcity
     *
     * @return void
     */
    public function setClearingBankcity(string $clearingBankcity): void
    {
        $this->clearing_bankcity = $clearingBankcity;
    }

    /**
     * @return string
     */
    public function getClearingBankcity(): string
    {
        return $this->clearing_bankcity;
    }

    /**
     * @param string $clearingBankcode
     *
     * @return void
     */
    public function setClearingBankcode(string $clearingBankcode): void
    {
        $this->clearing_bankcode = $clearingBankcode;
    }

    /**
     * @return string
     */
    public function getClearingBankcode(): string
    {
        return $this->clearing_bankcode;
    }

    /**
     * @param string $clearingBankcountry
     *
     * @return void
     */
    public function setClearingBankcountry(string $clearingBankcountry): void
    {
        $this->clearing_bankcountry = $clearingBankcountry;
    }

    /**
     * @return string
     */
    public function getClearingBankcountry(): string
    {
        return $this->clearing_bankcountry;
    }

    /**
     * @param string $clearingBankiban
     *
     * @return void
     */
    public function setClearingBankiban(string $clearingBankiban): void
    {
        $this->clearing_bankiban = $clearingBankiban;
    }

    /**
     * @return string
     */
    public function getClearingBankiban(): string
    {
        return $this->clearing_bankiban;
    }

    /**
     * @param string $clearingBankname
     *
     * @return void
     */
    public function setClearingBankname(string $clearingBankname): void
    {
        $this->clearing_bankname = $clearingBankname;
    }

    /**
     * @return string
     */
    public function getClearingBankname(): string
    {
        return $this->clearing_bankname;
    }

    /**
     * @param string $iban
     *
     * @return void
     */
    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     *
     * @return void
     */
    public function setBic(string $bic): void
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @param string $mandateIdentification
     *
     * @return void
     */
    public function setMandateIdentification(string $mandateIdentification): void
    {
        $this->mandate_identification = $mandateIdentification;
    }

    /**
     * @return string
     */
    public function getMandateIdentification(): string
    {
        return $this->mandate_identification;
    }

    /**
     * @param string $clearingDuedate
     *
     * @return void
     */
    public function setClearingDuedate(string $clearingDuedate): void
    {
        $this->clearing_duedate = $clearingDuedate;
    }

    /**
     * @return string
     */
    public function getClearingDuedate(): string
    {
        return $this->clearing_duedate;
    }

    /**
     * @param string $clearingAmount
     *
     * @return void
     */
    public function setClearingAmount(string $clearingAmount): void
    {
        $this->clearing_amount = $clearingAmount;
    }

    /**
     * @return string
     */
    public function getClearingAmount(): string
    {
        return $this->clearing_amount;
    }

    /**
     * @param string $creditorIdentifier
     *
     * @return void
     */
    public function setCreditorIdentifier(string $creditorIdentifier): void
    {
        $this->creditor_identifier = $creditorIdentifier;
    }

    /**
     * @return string
     */
    public function getCreditorIdentifier(): string
    {
        return $this->creditor_identifier;
    }

    /**
     * @param string $clearingDate
     *
     * @return void
     */
    public function setClearingDate(string $clearingDate): void
    {
        $this->clearing_date = $clearingDate;
    }

    /**
     * @return string
     */
    public function getClearingDate(): string
    {
        return $this->clearing_date;
    }

    /**
     * @param string $clearingInstructionnote
     *
     * @return void
     */
    public function setClearingInstructionnote(string $clearingInstructionnote): void
    {
        $this->clearing_instructionnote = $clearingInstructionnote;
    }

    /**
     * @return string
     */
    public function getClearingInstructionnote(): string
    {
        return $this->clearing_instructionnote;
    }

    /**
     * @param string $clearingLegalnote
     *
     * @return void
     */
    public function setClearingLegalnote(string $clearingLegalnote): void
    {
        $this->clearing_legalnote = $clearingLegalnote;
    }

    /**
     * @return string
     */
    public function getClearingLegalnote(): string
    {
        return $this->clearing_legalnote;
    }

    /**
     * @param string $clearingReference
     *
     * @return void
     */
    public function setClearingReference(string $clearingReference): void
    {
        $this->clearing_reference = $clearingReference;
    }

    /**
     * @return string
     */
    public function getClearingReference(): string
    {
        return $this->clearing_reference;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
