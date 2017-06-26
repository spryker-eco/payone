<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone\Dependency;

interface HashInterface
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function hash($value);

}
