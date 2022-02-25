<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class BusinessContainer extends AbstractContainer
{
    /**
     * @var string
     */
    protected $settleaccount;

    /**
     * @var string
     */
    protected $transactiontype;

    /**
     * @var string
     */
    protected $booking_date;

    /**
     * @var string
     */
    protected $document_date;

    /**
     * @param string $bookingDate
     *
     * @return void
     */
    public function setBookingDate(string $bookingDate): void
    {
        $this->booking_date = $bookingDate;
    }

    /**
     * @return string
     */
    public function getBookingDate(): string
    {
        return $this->booking_date;
    }

    /**
     * @param string $documentDate
     *
     * @return void
     */
    public function setDocumentDate(string $documentDate): void
    {
        $this->document_date = $documentDate;
    }

    /**
     * @return string
     */
    public function getDocumentDate(): string
    {
        return $this->document_date;
    }

    /**
     * @param string $settleAccount
     *
     * @return void
     */
    public function setSettleAccount(string $settleAccount): void
    {
        $this->settleaccount = $settleAccount;
    }

    /**
     * @return string
     */
    public function getSettleAccount(): string
    {
        return $this->settleaccount;
    }

    /**
     * @param string $transactionType
     *
     * @return void
     */
    public function setTransactionType(string $transactionType): void
    {
        $this->transactiontype = $transactionType;
    }

    /**
     * @return string
     */
    public function getTransactionType(): string
    {
        return $this->transactiontype;
    }
}
