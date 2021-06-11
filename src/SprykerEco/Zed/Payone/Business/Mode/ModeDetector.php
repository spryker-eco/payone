<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Mode;

use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Zed\Payone\PayoneConfig;

class ModeDetector implements ModeDetectorInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Payone\PayoneConfig $config
     */
    public function __construct(PayoneConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        $mode = $this->config->getMode();

        if ($mode === static::MODE_LIVE) {
            return static::MODE_LIVE;
        }

        return static::MODE_TEST;
    }
}
