<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Key;

interface HmacGeneratorInterface
{
    /**
     * @param string $string
     * @param string $key
     *
     * @return string
     */
    public function hash(string $string, string $key): string;
}
