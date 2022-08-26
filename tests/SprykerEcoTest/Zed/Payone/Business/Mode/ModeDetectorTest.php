<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Mode;

use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Zed\Payone\Business\Mode\ModeDetector;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Mode
 * @group ModeDetectorTest
 */
class ModeDetectorTest extends AbstractBusinessTest
{
    /**
     * @return void
     */
    public function testModeDetection(): void
    {
        $modeDetector = new ModeDetector(new PayoneConfig());
        $detectedMode = $modeDetector->getMode();

        $this->assertEquals(ModeDetectorInterface::MODE_TEST, $detectedMode);
    }
}
