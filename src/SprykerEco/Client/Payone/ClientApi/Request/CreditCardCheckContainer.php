<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Request;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class CreditCardCheckContainer extends AbstractRequest
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_CREDITCARDCHECK;

    /**
     * @var string
     */
    protected $storecarddata = PayoneApiConstants::STORE_CARD_DATA_YES;

    /**
     * @var string
     */
    protected $successurl;

    /**
     * @var string
     */
    protected $errorurl;

    /**
     * @param string $storecarddata
     *
     * @return void
     */
    public function setStorecarddata($storecarddata)
    {
        $this->storecarddata = $storecarddata;
    }

    /**
     * @return string
     */
    public function getStorecarddata()
    {
        return $this->storecarddata;
    }

    /**
     * @param string $errorurl
     *
     * @return void
     */
    public function setErrorurl($errorurl)
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @return string
     */
    public function getErrorurl()
    {
        return $this->errorurl;
    }

    /**
     * @param string $successurl
     *
     * @return void
     */
    public function setSuccessurl($successurl)
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string
     */
    public function getSuccessurl()
    {
        return $this->successurl;
    }
}
