<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Key;

class UrlHmacGenerator implements HmacGeneratorInterface
{
    /**
     * string
     *
     * @var string
     */
    public const HASH_ALGO = 'sha256';

    /**
     * @param string $string
     * @param string $key
     *
     * @return string
     */
    public function hash(string $string, string $key): string
    {
        return hash_hmac(static::HASH_ALGO, $string, $key);
    }
}
