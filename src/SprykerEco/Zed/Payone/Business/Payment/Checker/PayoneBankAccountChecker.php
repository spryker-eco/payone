<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\Checker;

use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\BankAccountCheckResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;

class PayoneBankAccountChecker implements PayoneBankAccountCheckerInterface
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
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PaymentMapperReaderInterface $paymentMapperReader,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->paymentMapperReader = $paymentMapperReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer): PayoneBankAccountCheckTransfer
    {
        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper(PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER);
        $requestContainer = $paymentMethodMapper->mapBankAccountCheck($bankAccountCheckTransfer);
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new BankAccountCheckResponseContainer($rawResponse);

        return $this->mapToBankAccountCheckTransfer($bankAccountCheckTransfer, $responseContainer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\BankAccountCheckResponseContainer $responseContainer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    protected function mapToBankAccountCheckTransfer(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer, BankAccountCheckResponseContainer $responseContainer): PayoneBankAccountCheckTransfer
    {
        $bankAccountCheckTransfer->setErrorCode($responseContainer->getErrorcode());
        $bankAccountCheckTransfer->setCustomerErrorMessage($responseContainer->getCustomermessage());
        $bankAccountCheckTransfer->setStatus($responseContainer->getStatus());
        $bankAccountCheckTransfer->setInternalErrorMessage($responseContainer->getErrormessage());

        return $bankAccountCheckTransfer;
    }
}
