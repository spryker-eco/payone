<?php

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class GenericPaymentResponseContainer extends AbstractResponseContainer
{

    /**
     * @var string
     */
    protected $workOrderId;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer
     */
    protected $paydata;

    /**
     * @var string
     */
    protected $redirectUrl;

    /**
     * @return string
     */
    public function getWorkOrderId()
    {
        return $this->workOrderId;
    }

    /**
     * @param string $workOrderId
     *
     * @return void
     */
    public function setWorkOrderId(string $workOrderId)
    {
        $this->workOrderId = $workOrderId;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer
     */
    public function getPaydata()
    {
        return $this->paydata;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer $paydata
     *
     * @return void
     */
    public function setPaydata(PaydataContainer $paydata)
    {
        $this->paydata = $paydata;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return void
     */
    public function setRedirectUrl(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

}