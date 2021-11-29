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
     * @var string
     */
    protected $document_date;

    /**
     * (YYYYMMDD)
     *
     * @var string
     */
    protected $booking_date;

    /**
     * (Unixtimestamp)
     *
     * @var string
     */
    protected $due_time;

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
     * @param string $due_time
     *
     * @return void
     */
    public function setDueTime(string $due_time): void
    {
        $this->due_time = $due_time;
    }

    /**
     * @return string
     */
    public function getDueTime(): string
    {
        return $this->due_time;
    }
}
