<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class BusinessContainer extends AbstractContainer
{
    /**
     * (YYYYMMDD)
     *
     * @var string|null
     */
    protected $document_date;

    /**
     * (YYYYMMDD)
     *
     * @var string|null
     */
    protected $booking_date;

    /**
     * (Unixtimestamp)
     *
     * @var string|null
     */
    protected $due_time;

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
     * @return string|null
     */
    public function getBookingDate(): ?string
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
     * @return string|null
     */
    public function getDocumentDate(): ?string
    {
        return $this->document_date;
    }

    /**
     * @param string $dueTime
     *
     * @return void
     */
    public function setDueTime(string $dueTime): void
    {
        $this->due_time = $dueTime;
    }

    /**
     * @return string|null
     */
    public function getDueTime(): ?string
    {
        return $this->due_time;
    }
}
