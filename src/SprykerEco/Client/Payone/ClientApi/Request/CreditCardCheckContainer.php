<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Request;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class CreditCardCheckContainer extends AbstractRequest implements CreditCardCheckContainerInterface
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
    public function setStorecarddata(string $storecarddata): void
    {
        $this->storecarddata = $storecarddata;
    }

    /**
     * @return string
     */
    public function getStorecarddata(): string
    {
        return $this->storecarddata;
    }

    /**
     * @param string $errorurl
     *
     * @return void
     */
    public function setErrorurl(string $errorurl): void
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @return string
     */
    public function getErrorurl(): string
    {
        return $this->errorurl;
    }

    /**
     * @param string $successurl
     *
     * @return void
     */
    public function setSuccessurl(string $successurl): void
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string
     */
    public function getSuccessurl(): string
    {
        return $this->successurl;
    }
}
