<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Request;

interface CreditCardCheckContainerInterface extends ContainerInterface
{
    /**
     * @param string $storecarddata
     *
     * @return void
     */
    public function setStorecarddata(string $storecarddata): void;

    /**
     * @return string
     */
    public function getStorecarddata(): string;

    /**
     * @param string $errorurl
     *
     * @return void
     */
    public function setErrorurl(string $errorurl): void;

    /**
     * @return string
     */
    public function getErrorurl(): string;

    /**
     * @param string $successurl
     *
     * @return void
     */
    public function setSuccessurl(string $successurl): void;

    /**
     * @return string
     */
    public function getSuccessurl(): string;
}
