<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

class BankAccountCheckAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    protected function getSuccessResponse(): array
    {
        $result = 'bankcountry=DE errorcode=200 status=OK customermessage=OK errormessage=OK';

        return explode(' ', $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse(): array
    {
        $result = 'status=ERROR';

        return explode(' ', $result);
    }
}
