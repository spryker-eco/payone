<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Checker;

use Generated\Shared\Transfer\CreditCardCheckResponseTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CreditCardCheckResponseMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;

class PayoneCreditCardChecker implements PayoneCreditCardCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standardParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CreditCardCheckResponseMapperInterface
     */
    protected $creditCardCheckResponseMapper;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standardParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Mapper\CreditCardCheckResponseMapperInterface $creditCardCheckResponseMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standardParameterMapper,
        PaymentMapperReaderInterface $paymentMapperReader,
        CreditCardCheckResponseMapperInterface $creditCardCheckResponseMapper
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->standardParameterMapper = $standardParameterMapper;
        $this->paymentMapperReader = $paymentMapperReader;
        $this->creditCardCheckResponseMapper = $creditCardCheckResponseMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Generated\Shared\Transfer\CreditCardCheckResponseTransfer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData): CreditCardCheckResponseTransfer
    {
        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper($creditCardData->getPaymentOrFail()->getPaymentMethodOrFail());
        $requestContainer = $paymentMethodMapper->mapCreditCardCheck($creditCardData);

        $this->standardParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new CreditCardCheckResponseContainer($rawResponse);

        return $this->creditCardCheckResponseMapper->getCreditCardCheckResponseTransfer($responseContainer);
    }
}
