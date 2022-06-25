<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class ThreeDSecureCheckContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_3DSECURE_CHECK;

    /**
     * @var string|null
     */
    protected $aid;

    /**
     * @var int|null
     */
    protected $amount;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var string|null
     */
    protected $clearingtype;

    /**
     * @var string|null
     */
    protected $exiturl;

    /**
     * @var string|null
     */
    protected $cardpan;

    /**
     * @var string|null
     */
    protected $cardtype;

    /**
     * @var string|null
     */
    protected $cardexpiredate;

    /**
     * @var int|null
     */
    protected $cardcvc2;

    /**
     * @var string|null
     */
    protected $storecarddata;

    /**
     * @var string|null
     */
    protected $language;

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
     * @param int $cardcvc2
     *
     * @return void
     */
    public function setCardCvc2(int $cardcvc2): void
    {
        $this->cardcvc2 = $cardcvc2;
    }

    /**
     * @return int|null
     */
    public function getCardCvc2(): ?int
    {
        return $this->cardcvc2;
    }

    /**
     * @param string $cardexpiredate
     *
     * @return void
     */
    public function setCardExpireDate(string $cardexpiredate): void
    {
        $this->cardexpiredate = $cardexpiredate;
    }

    /**
     * @return string|null
     */
    public function getCardExpireDate(): ?string
    {
        return $this->cardexpiredate;
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
     * @return string|null
     */
    public function getCardPan(): ?string
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
     * @return string|null
     */
    public function getCardType(): ?string
    {
        return $this->cardtype;
    }

    /**
     * @param string $clearingtype
     *
     * @return void
     */
    public function setClearingType(string $clearingtype): void
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string|null
     */
    public function getClearingType(): ?string
    {
        return $this->clearingtype;
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
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string $exiturl
     *
     * @return void
     */
    public function setExitUrl(string $exiturl): void
    {
        $this->exiturl = $exiturl;
    }

    /**
     * @return string|null
     */
    public function getExitUrl(): ?string
    {
        return $this->exiturl;
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
