<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture;

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
     * ENUM
     *
     * @var string
     */
    protected $settleaccount;

    /**
     * @var array
     */
    protected $it;

    /**
     * @var array
     */
    protected $id;

    /**
     * @var array
     */
    protected $pr;

    /**
     * @var array
     */
    protected $no;

    /**
     * @var array
     */
    protected $de;

    /**
     * @var array
     */
    protected $va;

    /**
     * @var string
     */
    protected $email;

    /**
     * @param string $booking_date
     *
     * @return void
     */
    public function setBookingDate($booking_date): void
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
    public function setDocumentDate($document_date): void
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
    public function setDueTime($due_time): void
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

    /**
     * @param string $settleaccount
     *
     * @return void
     */
    public function setSettleAccount($settleaccount): void
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
     * @return array
     */
    public function getIt(): array
    {
        return $this->it;
    }

    /**
     * @param array $it
     *
     * @return void
     */
    public function setIt(array $it): void
    {
        $this->it = $it;
    }

    /**
     * @return array
     */
    public function getId(): array
    {
        return $this->id;
    }

    /**
     * @param array $id
     *
     * @return void
     */
    public function setId(array $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getPr(): array
    {
        return $this->pr;
    }

    /**
     * @param array $pr
     *
     * @return void
     */
    public function setPr(array $pr): void
    {
        $this->pr = $pr;
    }

    /**
     * @return array
     */
    public function getNo(): array
    {
        return $this->no;
    }

    /**
     * @param array $no
     *
     * @return void
     */
    public function setNo(array $no): void
    {
        $this->no = $no;
    }

    /**
     * @return array
     */
    public function getDe(): array
    {
        return $this->de;
    }

    /**
     * @param array $de
     *
     * @return void
     */
    public function setDe(array $de): void
    {
        $this->de = $de;
    }

    /**
     * @return array
     */
    public function getVa(): array
    {
        return $this->va;
    }

    /**
     * @param array $va
     *
     * @return void
     */
    public function setVa(array $va): void
    {
        $this->va = $va;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
