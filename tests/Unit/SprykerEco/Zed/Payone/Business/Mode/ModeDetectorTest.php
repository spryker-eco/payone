<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Payone\Business\Mode;

use PHPUnit_Framework_TestCase;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Zed\Payone\Business\Mode\ModeDetector;
use SprykerEco\Zed\Payone\PayoneConfig;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Mode
 * @group ModeDetectorTest
 */
class ModeDetectorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testModeDetection()
    {
        $modeDetector = new ModeDetector(new PayoneConfig());
        $detectedMode = $modeDetector->getMode();

        $this->assertEquals(ModeDetectorInterface::MODE_TEST, $detectedMode);
    }

}
