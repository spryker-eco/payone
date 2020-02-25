<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

class SetExpressCheckoutAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    protected function getSuccessResponse()
    {
        $result = 'status=REDIRECT' .
            ' redirecturl=https://www.sandbox.paypal.com/webscr?useraction=continue&cmd=_express-checkout&token=EC-1XL23256WD360340A' .
            ' workorderid=WX1A1SE572GTG4FF';

        return explode(" ", $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse()
    {
        $result = 'status=ERROR' .
            ' errorcode=916' .
            ' workorderid=WX1A1SEJB38UELUY' .
            ' errormessage=Amount error' .
            ' customermessage=An error occured while processing this transaction (wrong parameters).';

        return explode(" ", $result);
    }
}
