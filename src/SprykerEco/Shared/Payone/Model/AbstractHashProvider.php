<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone\Model;

use SprykerEco\Shared\Payone\Dependency\HashInterface;

abstract class AbstractHashProvider implements HashInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function hash(string $value): string
    {
        return hash('md5', $value);
    }
}
