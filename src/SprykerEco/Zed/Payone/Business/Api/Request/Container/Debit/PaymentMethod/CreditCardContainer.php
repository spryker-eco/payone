<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod;

class CreditCardContainer extends AbstractPaymentMethodContainer
{
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
    protected $cardholder;

    /**
     * @var string
     */
    protected $pseudocardpan;

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
     * @param string $cardholder
     *
     * @return void
     */
    public function setCardHolder(string $cardholder): void
    {
        $this->cardholder = $cardholder;
    }

    /**
     * @return string
     */
    public function getCardHolder(): string
    {
        return $this->cardholder;
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
     * @param string $pseudocardpan
     *
     * @return void
     */
    public function setPseudoCardPan(string $pseudocardpan): void
    {
        $this->pseudocardpan = $pseudocardpan;
    }

    /**
     * @return string
     */
    public function getPseudoCardPan(): string
    {
        return $this->pseudocardpan;
    }
}
