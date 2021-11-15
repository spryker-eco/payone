<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class CreditCardCheckResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected $pseudocardpan;

    /**
     * @var string
     */
    protected $truncatedcardpan;

    /**
     * @param string $truncatedcardpan
     *
     * @return void
     */
    public function setTruncatedcardpan($truncatedcardpan): void
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
     * @param string $pseudocardpan
     *
     * @return void
     */
    public function setPseudocardpan($pseudocardpan): void
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
}
