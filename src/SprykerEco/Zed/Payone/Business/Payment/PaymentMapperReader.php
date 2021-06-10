<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Business\Exception\InvalidPaymentMethodException;
use SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

class PaymentMapperReader implements PaymentMapperReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    protected $sequenceNumberProvider;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface
     */
    protected $urlHmacGenerator;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @param \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     * @param \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface $urlHmacGenerator
     */
    public function __construct(SequenceNumberProviderInterface $sequenceNumberProvider, HmacGeneratorInterface $urlHmacGenerator)
    {
        $this->sequenceNumberProvider = $sequenceNumberProvider;
        $this->urlHmacGenerator = $urlHmacGenerator;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface $paymentMethodMapper
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     *
     * @return void
     */
    public function registerPaymentMethodMapper(PaymentMethodMapperInterface $paymentMethodMapper, PayoneStandardParameterTransfer $standardParameter): void
    {
        $paymentMethodMapper->setStandardParameter($standardParameter);
        $paymentMethodMapper->setSequenceNumberProvider($this->sequenceNumberProvider);
        $paymentMethodMapper->setUrlHmacGenerator($this->urlHmacGenerator);
        $this->registeredMethodMappers[$paymentMethodMapper->getName()] = $paymentMethodMapper;
    }

    /**
     * @param string $paymentMethodName
     *
     * @throws \SprykerEco\Zed\Payone\Business\Exception\InvalidPaymentMethodException
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    public function getRegisteredPaymentMethodMapper(string $paymentMethodName): PaymentMethodMapperInterface
    {
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($paymentMethodName);
        if ($paymentMethodMapper === null) {
            throw new InvalidPaymentMethodException(
                sprintf('No registered payment method mapper found for given method name %s', $paymentMethodName)
            );
        }

        return $paymentMethodMapper;
    }

    /**
     * @param string $name
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface|null
     */
    protected function findPaymentMethodMapperByName(string $name): ?PaymentMethodMapperInterface
    {
        if (array_key_exists($name, $this->registeredMethodMappers)) {
            return $this->registeredMethodMappers[$name];
        }

        return null;
    }
}
