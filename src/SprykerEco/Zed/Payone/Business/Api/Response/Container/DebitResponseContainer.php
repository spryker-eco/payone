<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class DebitResponseContainer extends AbstractResponseContainer
{
    /**
     * @var int|null
     */
    protected $txid;

    /**
     * @var string|null
     */
    protected $settleaccount;

    /**
     * @param string $settleaccount
     *
     * @return void
     */
    public function setSettleaccount(string $settleaccount): void
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string|null
     */
    public function getSettleaccount(): ?string
    {
        return $this->settleaccount;
    }

    /**
     * @param int $txid
     *
     * @return void
     */
    public function setTxid(int $txid): void
    {
        $this->txid = $txid;
    }

    /**
     * @return int|null
     */
    public function getTxid(): ?int
    {
        return $this->txid;
    }
}
