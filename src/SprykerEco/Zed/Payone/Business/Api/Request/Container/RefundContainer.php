<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer;

class RefundContainer extends AbstractRequestContainer implements RefundContainerInterface
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_REFUND;

    /**
     * @var int
     */
    protected $txid;

    /**
     * @var int|null
     */
    protected $sequencenumber;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var string|null
     */
    protected $narrative_text;

    /**
     * @var string
     */
    protected $use_customerdata;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer
     */
    protected $paymentMethod;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    protected $invoicing;

    /**
     * @var string|null
     */
    protected $bankcountry;

    /**
     * @var string|null
     */
    protected $bankaccount;

    /**
     * @var string|null
     */
    protected $bankcode;

    /**
     * @var string|null
     */
    protected $bankbranchcode;

    /**
     * @var string|null
     */
    protected $bankcheckdigit;

    /**
     * @var string|null
     */
    protected $iban;

    /**
     * @var string|null
     */
    protected $bic;

    /**
     * @param int $amount
     *   Amount of refund (in smallest currency unit! e.g.
     *   cent). The amount must be less than or equal to
     *   the amount of the corresponding booking.
     *   (Always provide a negative amount)
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setAmount(int $amount): RefundContainerInterface
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param string|null $currency
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setCurrency(?string $currency): RefundContainerInterface
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $narrative_text
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setNarrativeText(?string $narrative_text): RefundContainerInterface
    {
        $this->narrative_text = $narrative_text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNarrativeText(): ?string
    {
        return $this->narrative_text;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setInvoicing(TransactionContainer $invoicing): RefundContainerInterface
    {
        $this->invoicing = $invoicing;

        return $this;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing(): TransactionContainer
    {
        return $this->invoicing;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer $paymentMethod
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setPaymentMethod(BankAccountContainer $paymentMethod): RefundContainerInterface
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer
     */
    public function getPaymentMethod(): BankAccountContainer
    {
        return $this->paymentMethod;
    }

    /**
     * @param int|null $sequencenumber
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setSequenceNumber(?int $sequencenumber): RefundContainerInterface
    {
        $this->sequencenumber = $sequencenumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequencenumber;
    }

    /**
     * @param int $txid
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setTxid(int $txid): RefundContainerInterface
    {
        $this->txid = $txid;

        return $this;
    }

    /**
     * @return int
     */
    public function getTxid(): int
    {
        return $this->txid;
    }

    /**
     * @param string $use_customerdata
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setUseCustomerData(string $use_customerdata): RefundContainerInterface
    {
        $this->use_customerdata = $use_customerdata;

        return $this;
    }

    /**
     * @return string
     */
    public function getUseCustomerData(): string
    {
        return $this->use_customerdata;
    }

    /**
     * @return string
     */
    public function getBankcountry(): string
    {
        return $this->bankcountry;
    }

    /**
     * @param string|null $bankcountry
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setBankcountry(?string $bankcountry): RefundContainerInterface
    {
        $this->bankcountry = $bankcountry;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankaccount(): ?string
    {
        return $this->bankaccount;
    }

    /**
     * @param string|null $bankaccount
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setBankaccount(?string $bankaccount): RefundContainerInterface
    {
        $this->bankaccount = $bankaccount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankcode(): ?string
    {
        return $this->bankcode;
    }

    /**
     * @param string|null $bankcode
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setBankcode(?string $bankcode): RefundContainerInterface
    {
        $this->bankcode = $bankcode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankbranchcode(): ?string
    {
        return $this->bankbranchcode;
    }

    /**
     * @param string|null $bankbranchcode
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setBankbranchcode(?string $bankbranchcode): RefundContainerInterface
    {
        $this->bankbranchcode = $bankbranchcode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankcheckdigit(): ?string
    {
        return $this->bankcheckdigit;
    }

    /**
     * @param string|null $bankcheckdigit
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setBankcheckdigit(?string $bankcheckdigit): RefundContainerInterface
    {
        $this->bankcheckdigit = $bankcheckdigit;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param string|null $iban
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setIban(?string $iban): RefundContainerInterface
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBic(): ?string
    {
        return $this->bic;
    }

    /**
     * @param string|null $bic
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function setBic(?string $bic): RefundContainerInterface
    {
        $this->bic = $bic;

        return $this;
    }
}
