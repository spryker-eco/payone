<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone\Dependency;

interface ModeDetectorInterface
{

    const MODE_TEST = 'test';
    const MODE_LIVE = 'live';

    /**
     * @return string
     */
    public function getMode();

}
