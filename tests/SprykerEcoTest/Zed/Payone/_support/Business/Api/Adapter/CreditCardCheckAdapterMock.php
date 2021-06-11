<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

class CreditCardCheckAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    protected function getSuccessResponse()
    {
        $result = 'pseudoCardPan=1111111111111111';

        return explode(' ', $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse()
    {
        $result = 'status=ERROR';

        return explode(' ', $result);
    }
}
