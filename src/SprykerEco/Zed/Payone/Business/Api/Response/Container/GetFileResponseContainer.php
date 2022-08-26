<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class GetFileResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string|null
     */
    protected $DATA;

    /**
     * @return string|null
     */
    public function getDATA(): ?string
    {
        return $this->DATA;
    }

    /**
     * @param string $DATA
     *
     * @return void
     */
    public function setDATA(string $DATA): void
    {
        $this->DATA = $DATA;
    }
}
