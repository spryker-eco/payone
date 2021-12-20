<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Mode;

use Spryker\Shared\Config\Environment;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;

/**
 * @deprecated Use Zed one instead
 */
class ModeDetector implements ModeDetectorInterface
{
    /**
     * @deprecated Will be removed. Needs refactoring to use Zed getMode().
     *
     * @return string
     */
    public function getMode(): string
    {
        if (Environment::isNotProduction()) {
            return static::MODE_TEST;
        }

        return static::MODE_LIVE;
    }
}
