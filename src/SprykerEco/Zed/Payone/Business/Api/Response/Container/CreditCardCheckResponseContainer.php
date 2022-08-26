<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class CreditCardCheckResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string|null
     */
    protected $pseudocardpan;

    /**
     * @var string|null
     */
    protected $truncatedcardpan;

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
     * @return string|null
     */
    public function getTruncatedcardpan(): ?string
    {
        return $this->truncatedcardpan;
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
     * @return string|null
     */
    public function getPseudocardpan(): ?string
    {
        return $this->pseudocardpan;
    }
}
