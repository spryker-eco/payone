<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class ThreeDSecureCheckResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected $acsurl;

    /**
     * @var string
     */
    protected $termurl;

    /**
     * @var string
     */
    protected $pareq;

    /**
     * @var string
     */
    protected $xid;

    /**
     * @var string
     */
    protected $md;

    /**
     * @var string
     */
    protected $pseudocardpan;

    /**
     * @var string
     */
    protected $truncatedcardpan;

    /**
     * @param string $acsurl
     *
     * @return void
     */
    public function setAcsurl(string $acsurl): void
    {
        $this->acsurl = $acsurl;
    }

    /**
     * @return string
     */
    public function getAcsurl(): string
    {
        return $this->acsurl;
    }

    /**
     * @param string $md
     *
     * @return void
     */
    public function setMd(string $md): void
    {
        $this->md = $md;
    }

    /**
     * @return string
     */
    public function getMd(): string
    {
        return $this->md;
    }

    /**
     * @param string $pareq
     *
     * @return void
     */
    public function setPareq(string $pareq): void
    {
        $this->pareq = $pareq;
    }

    /**
     * @return string
     */
    public function getPareq(): string
    {
        return $this->pareq;
    }

    /**
     * @param string $pseudocardpan
     *
     * @return void
     */
    public function setPseudocardpan(string $pseudocardpan): void
    {
        $this->pseudocardpan = $pseudocardpan;
    }

    /**
     * @return string
     */
    public function getPseudocardpan(): string
    {
        return $this->pseudocardpan;
    }

    /**
     * @param string $termurl
     *
     * @return void
     */
    public function setTermurl(string $termurl): void
    {
        $this->termurl = $termurl;
    }

    /**
     * @return string
     */
    public function getTermurl(): string
    {
        return $this->termurl;
    }

    /**
     * @param string $truncatedcardpan
     *
     * @return void
     */
    public function setTruncatedcardpan(string $truncatedcardpan): void
    {
        $this->truncatedcardpan = $truncatedcardpan;
    }

    /**
     * @return string
     */
    public function getTruncatedcardpan(): string
    {
        return $this->truncatedcardpan;
    }

    /**
     * @param string $xid
     *
     * @return void
     */
    public function setXid(string $xid): void
    {
        $this->xid = $xid;
    }

    /**
     * @return string
     */
    public function getXid(): string
    {
        return $this->xid;
    }
}
