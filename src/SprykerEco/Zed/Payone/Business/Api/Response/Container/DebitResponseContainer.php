<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class DebitResponseContainer extends AbstractResponseContainer
{
    /**
     * @var int
     */
    protected $txid;

    /**
     * @var string
     */
    protected $settleaccount;

    /**
     * @param string $settleaccount
     *
     * @return void
     */
    public function setSettleaccount($settleaccount): void
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string
     */
    public function getSettleaccount(): string
    {
        return $this->settleaccount;
    }

    /**
     * @param int $txid
     *
     * @return void
     */
    public function setTxid($txid): void
    {
        $this->txid = $txid;
    }

    /**
     * @return int
     */
    public function getTxid(): int
    {
        return $this->txid;
    }
}
