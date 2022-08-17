<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer;

interface RefundContainerInterface
{
    /**
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount(int $amount): self;

    /**
     * @return int
     */
    public function getAmount(): int;

    /**
     * @param string|null $currency
     *
     * @return $this
     */
    public function setCurrency(?string $currency): self;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @param string|null $narrativeText
     *
     * @return $this
     */
    public function setNarrativeText(?string $narrativeText): self;

    /**
     * @return string|null
     */
    public function getNarrativeText(): ?string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return $this
     */
    public function setInvoicing(TransactionContainer $invoicing): self;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing(): TransactionContainer;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod(BankAccountContainer $paymentMethod): self;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer
     */
    public function getPaymentMethod(): BankAccountContainer;

    /**
     * @param int|null $sequencenumber
     *
     * @return $this
     */
    public function setSequenceNumber(?int $sequencenumber): self;

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int;

    /**
     * @param int|null $txid
     *
     * @return $this
     */
    public function setTxid(?int $txid): self;

    /**
     * @return int|null
     */
    public function getTxid(): ?int;

    /**
     * @param string $use_customerdata
     *
     * @return $this
     */
    public function setUseCustomerData(string $use_customerdata): self;

    /**
     * @return string
     */
    public function getUseCustomerData(): string;

    /**
     * @return string|null
     */
    public function getBankcountry(): ?string;

    /**
     * @param string|null $bankcountry
     *
     * @return $this
     */
    public function setBankcountry(?string $bankcountry): self;

    /**
     * @return string|null
     */
    public function getBankaccount(): ?string;

    /**
     * @param string|null $bankaccount
     *
     * @return $this
     */
    public function setBankaccount(?string $bankaccount): self;

    /**
     * @return string|null
     */
    public function getBankcode(): ?string;

    /**
     * @param string|null $bankcode
     *
     * @return $this
     */
    public function setBankcode(?string $bankcode): self;

    /**
     * @return string|null
     */
    public function getBankbranchcode(): ?string;

    /**
     * @param string|null $bankbranchcode
     *
     * @return $this
     */
    public function setBankbranchcode(?string $bankbranchcode): self;

    /**
     * @return string|null
     */
    public function getBankcheckdigit(): ?string;

    /**
     * @param string|null $bankcheckdigit
     *
     * @return $this
     */
    public function setBankcheckdigit(?string $bankcheckdigit): self;

    /**
     * @return string|null
     */
    public function getIban(): ?string;

    /**
     * @param string|null $iban
     *
     * @return $this
     */
    public function setIban(?string $iban): self;

    /**
     * @return string|null
     */
    public function getBic(): ?string;

    /**
     * @param string|null $bic
     *
     * @return $this
     */
    public function setBic(?string $bic): self;
}
