<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

interface DebitContainerInterface
{
    /**
     * @param int $amount
     *
     * @return void
     */
    public function setAmount(int $amount): void;

    /**
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer $business
     *
     * @return void
     */
    public function setBusiness(BusinessContainer $business): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer
     */
    public function getBusiness(): BusinessContainer;

    /**
     * @param string $clearingtype
     *
     * @return void
     */
    public function setClearingType(string $clearingtype): void;

    /**
     * @return string
     */
    public function getClearingType(): string;

    /**
     * @param string|null $currency
     *
     * @return void
     */
    public function setCurrency(?string $currency): void;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing(): TransactionContainer;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer
     */
    public function getPaymentMethod(): AbstractPaymentMethodContainer;

    /**
     * @param int|null $sequencenumber
     *
     * @return void
     */
    public function setSequenceNumber(?int $sequencenumber = null): void;

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int;

    /**
     * @param int|null $txid
     *
     * @return void
     */
    public function setTxid(?int $txid = null): void;

    /**
     * @return int|null
     */
    public function getTxid(): ?int;

    /**
     * @param string $use_customerdata
     *
     * @return void
     */
    public function setUseCustomerData(string $use_customerdata): void;

    /**
     * @return string
     */
    public function getUseCustomerData(): string;
}
