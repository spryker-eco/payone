<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

class CaptureContainer extends AbstractRequestContainer implements CaptureContainerInterface
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_CAPTURE;

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
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer
     */
    protected $business;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    protected $invoicing;

    /**
     * @var string
     */
    protected $capturemode;

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
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
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
     * @param ?int $sequencenumber
     *
     * @return void
     */
    public function setSequenceNumber(?int $sequencenumber): void
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
     * @return string|null
     */
    public function getTxid(): ?string
    {
        return $this->txid;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer $business
     *
     * @return void
     */
    public function setBusiness(BusinessContainer $business): void
    {
        $this->business = $business;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer|null
     */
    public function getBusiness(): ?BusinessContainer
    {
        return $this->business;
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
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer|null
     */
    public function getInvoicing(): ?TransactionContainer
    {
        return $this->invoicing;
    }

    /**
     * @return string
     */
    public function getCaptureMode(): string
    {
        return $this->capturemode;
    }

    /**
     * @param string $captureMode
     *
     * @return void
     */
    public function setCaptureMode(string $captureMode): void
    {
        $this->capturemode = $captureMode;
    }
}
