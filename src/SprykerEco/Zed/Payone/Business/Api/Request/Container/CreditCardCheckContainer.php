<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class CreditCardCheckContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_CREDITCARDCHECK;

    /**
     * @var int
     */
    protected $aid;

    /**
     * @var string
     */
    protected $cardpan;

    /**
     * @var string
     */
    protected $cardtype;

    /**
     * @var int
     */
    protected $cardexpiredate;

    /**
     * @var int
     */
    protected $cardcvc2;

    /**
     * @var int
     */
    protected $cardissuenumber;

    /**
     * @var string
     */
    protected $storecarddata;

    /**
     * @var string
     */
    protected $language;

    /**
     * @param int $cardcvc2
     *
     * @return void
     */
    public function setCardCvc2(int $cardcvc2): void
    {
        $this->cardcvc2 = $cardcvc2;
    }

    /**
     * @return int
     */
    public function getCardCvc2(): int
    {
        return $this->cardcvc2;
    }

    /**
     * @param int $cardexpiredate
     *
     * @return void
     */
    public function setCardExpireDate(int $cardexpiredate): void
    {
        $this->cardexpiredate = $cardexpiredate;
    }

    /**
     * @return int
     */
    public function getCardExpireDate(): int
    {
        return $this->cardexpiredate;
    }

    /**
     * @param int $cardissuenumber
     *
     * @return void
     */
    public function setCardIssueNumber(int $cardissuenumber): void
    {
        $this->cardissuenumber = $cardissuenumber;
    }

    /**
     * @return int
     */
    public function getCardIssueNumber(): int
    {
        return $this->cardissuenumber;
    }

    /**
     * @param string $cardpan
     *
     * @return void
     */
    public function setCardPan(string $cardpan): void
    {
        $this->cardpan = $cardpan;
    }

    /**
     * @return string
     */
    public function getCardPan(): string
    {
        return $this->cardpan;
    }

    /**
     * @param string $cardtype
     *
     * @return void
     */
    public function setCardType(string $cardtype): void
    {
        $this->cardtype = $cardtype;
    }

    /**
     * @return string
     */
    public function getCardType(): string
    {
        return $this->cardtype;
    }

    /**
     * @param string $storecarddata
     *
     * @return void
     */
    public function setStoreCardData(string $storecarddata): void
    {
        $this->storecarddata = $storecarddata;
    }

    /**
     * @return string|null
     */
    public function getStoreCardData(): ?string
    {
        return $this->storecarddata;
    }
}
