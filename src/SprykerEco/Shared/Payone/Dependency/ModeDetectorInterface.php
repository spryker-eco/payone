<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone\Dependency;

interface ModeDetectorInterface
{
    /**
     * @var string
     */
    public const MODE_TEST = 'test';

    /**
     * @var string
     */
    public const MODE_LIVE = 'live';

    /**
     * @return string
     */
    public function getMode(): string;
}
