<?php


namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;


use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\ManageMandateResponseContainer;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\DiscountMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ProductsMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\ShipmentMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager;
use SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class PayoneManageMandateMethodSender implements PayoneManageMandateMethodSenderInterface
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
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager
     */
    protected $paymentMapperManager;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperManager $paymentMapperManager
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper,
        PaymentMapperManager $paymentMapperManager
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->paymentMapperManager = $paymentMapperManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(PayoneManageMandateTransfer $manageMandateTransfer): PayoneManageMandateTransfer
    {
        /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\DirectDebit $paymentMethodMapper */
        $paymentMethodMapper = $this->paymentMapperManager->getRegisteredPaymentMethodMapper(PayoneApiConstants::PAYMENT_METHOD_DIRECT_DEBIT);
        $requestContainer = $paymentMethodMapper->mapManageMandate($manageMandateTransfer);
        $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new ManageMandateResponseContainer($rawResponse);

        $manageMandateTransfer->setErrorCode($responseContainer->getErrorcode());
        $manageMandateTransfer->setCustomerErrorMessage($responseContainer->getCustomermessage());
        $manageMandateTransfer->setStatus($responseContainer->getStatus());
        $manageMandateTransfer->setInternalErrorMessage($responseContainer->getErrormessage());
        $manageMandateTransfer->setMandateIdentification($responseContainer->getMandateIdentification());
        $manageMandateTransfer->setMandateText($responseContainer->getMandateText());
        $manageMandateTransfer->setIban($responseContainer->getIban());
        $manageMandateTransfer->setBic($responseContainer->getBic());

        return $manageMandateTransfer;
    }
}
