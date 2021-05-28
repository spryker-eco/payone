<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

class DebitContainer extends AbstractRequestContainer implements DebitContainerInterface
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_DEBIT;

    /**
     * @var string
     */
    protected $txid;

    /**
     * @var int
     */
    protected $sequencenumber;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $clearingtype;

    /**
     * @var string
     */
    protected $use_customerdata;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer
     */
    protected $business;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer
     */
    protected $paymentMethod;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    protected $invoicing;

    /**
     * @param int $amount
     *
     * @return void
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer $business
     *
     * @return void
     */
    public function setBusiness(BusinessContainer $business): void
    {
        $this->business = $business;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer
     */
    public function getBusiness(): BusinessContainer
    {
        return $this->business;
    }

    /**
     * @param string $clearingtype
     *
     * @return void
     */
    public function setClearingType(string $clearingtype): void
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string
     */
    public function getClearingType(): string
    {
        return $this->clearingtype;
    }

    /**
     * @param string|null $currency
     *
     * @return void
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing): void
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing(): TransactionContainer
    {
        return $this->invoicing;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer
     */
    public function getPaymentMethod(): AbstractPaymentMethodContainer
    {
        return $this->paymentMethod;
    }

    /**
     * @param int $sequencenumber
     *
     * @return void
     */
    public function setSequenceNumber(int $sequencenumber): void
    {
        $this->sequencenumber = $sequencenumber;
    }

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequencenumber;
    }

    /**
     * @param string $txid
     *
     * @return void
     */
    public function setTxid(string $txid): void
    {
        $this->txid = $txid;
    }

    /**
     * @return string
     */
    public function getTxid(): string
    {
        return $this->txid;
    }

    /**
     * @param string $use_customerdata
     *
     * @return void
     */
    public function setUseCustomerData(string $use_customerdata): void
    {
        $this->use_customerdata = $use_customerdata;
    }

    /**
     * @return string
     */
    public function getUseCustomerData(): string
    {
        return $this->use_customerdata;
    }
}
