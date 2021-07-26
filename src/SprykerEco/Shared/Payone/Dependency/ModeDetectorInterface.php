<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone\Dependency;

interface ModeDetectorInterface
{
    public const MODE_TEST = 'test';
    public const MODE_LIVE = 'live';

    /**
     * @return string
     */
    public function getMode(): string;
}
