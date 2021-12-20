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
     * @param string $booking_date
     *
     * @return void
     */
    public function setBookingDate(string $booking_date): void
    {
        $this->booking_date = $booking_date;
    }

    /**
     * @return string
     */
    public function getBookingDate(): string
    {
        return $this->booking_date;
    }

    /**
     * @param string $document_date
     *
     * @return void
     */
    public function setDocumentDate(string $document_date): void
    {
        $this->document_date = $document_date;
    }

    /**
     * @return string
     */
    public function getDocumentDate(): string
    {
        return $this->document_date;
    }

    /**
     * @param string $settleaccount
     *
     * @return void
     */
    public function setSettleAccount(string $settleaccount): void
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string
     */
    public function getSettleAccount(): string
    {
        return $this->settleaccount;
    }

    /**
     * @param string $transactiontype
     *
     * @return void
     */
    public function setTransactionType(string $transactiontype): void
    {
        $this->transactiontype = $transactiontype;
    }

    /**
     * @return string
     */
    public function getTransactionType(): string
    {
        return $this->transactiontype;
    }
}
