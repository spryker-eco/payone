<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Key;

use PHPUnit_Framework_TestCase;
use SprykerEco\Zed\Payone\Business\Key\HashProvider;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Key
 * @group KeyHashProviderTest
 */
class KeyHashProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testKeyHash()
    {
        $key = 'abcd1efgh2ijklm3nopq4';
        $expectedHashedKey = hash('md5', $key);

        $keyHashProvider = new HashProvider();
        $systemHashedKey = $keyHashProvider->hash($key);

        $this->assertEquals($expectedHashedKey, $systemHashedKey);
    }
}
