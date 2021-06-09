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
    public function setTruncatedcardpan($truncatedcardpan)
    {
        $this->truncatedcardpan = $truncatedcardpan;
    }

    /**
     * @return string
     */
    public function getTruncatedcardpan()
    {
        return $this->truncatedcardpan;
    }

    /**
     * @param string $pseudocardpan
     *
     * @return void
     */
    public function setPseudocardpan($pseudocardpan)
    {
        $this->pseudocardpan = $pseudocardpan;
    }

    /**
     * @return string
     */
    public function getPseudocardpan()
    {
        return $this->pseudocardpan;
    }
}
