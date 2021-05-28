<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

interface CaptureContainerInterface
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
     * @param string $currency
     *
     * @return void
     */
    public function setCurrency(string $currency): void;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @param int|null $sequencenumber
     *
     * @return void
     */
    public function setSequenceNumber(?int $sequencenumber): void;

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int;

    /**
     * @param string $txid
     *
     * @return void
     */
    public function setTxid(string $txid): void;

    /**
     * @return string|null
     */
    public function getTxid(): ?string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer $business
     *
     * @return void
     */
    public function setBusiness(BusinessContainer $business): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer|null
     */
    public function getBusiness(): ?BusinessContainer;

    /**
     * @param Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer|null
     */
    public function getInvoicing(): ?TransactionContainer;

    /**
     * @return string
     */
    public function getCaptureMode(): string;

    /**
     * @param string $captureMode
     *
     * @return void
     */
    public function setCaptureMode(string $captureMode): void;
}
