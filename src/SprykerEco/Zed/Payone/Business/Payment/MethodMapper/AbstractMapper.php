<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

abstract class AbstractMapper implements PaymentMethodMapperInterface
{
    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    private $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    private $sequenceNumberProvider;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface
     */
    private $urlHmacGenerator;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $storeConfig;

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     */
    public function __construct(Store $storeConfig)
    {
        $this->storeConfig = $storeConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameterTransfer
     *
     * @return void
     */
    public function setStandardParameter(PayoneStandardParameterTransfer $standardParameterTransfer): void
    {
        $this->standardParameter = $standardParameterTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function getStandardParameter()
    {
        return $this->standardParameter;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface $urlHmacGenerator
     *
     * @return void
     */
    public function setUrlHmacGenerator(HmacGeneratorInterface $urlHmacGenerator): void
    {
        $this->urlHmacGenerator = $urlHmacGenerator;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface
     */
    protected function getUrlHmacGenerator()
    {
        return $this->urlHmacGenerator;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     *
     * @return void
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider): void
    {
        $this->sequenceNumberProvider = $sequenceNumberProvider;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    protected function getSequenceNumberProvider()
    {
        return $this->sequenceNumberProvider;
    }

    /**
     * @param string $transactionId
     *
     * @return int
     */
    protected function getNextSequenceNumber($transactionId)
    {
        $nextSequenceNumber = $this->getSequenceNumberProvider()->getNextSequenceNumber($transactionId);

        return $nextSequenceNumber;
    }

    /**
     * @param string $orderReference
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected function createRedirectContainer($orderReference)
    {
        $redirectContainer = new RedirectContainer();

        $sig = $this->getUrlHmacGenerator()->hash($orderReference, $this->getStandardParameter()->getKey());
        $params = '?orderReference=' . $orderReference . '&sig=' . $sig;

        $redirectContainer->setSuccessUrl($this->getStandardParameter()->getRedirectSuccessUrl() . $params);
        $redirectContainer->setBackUrl($this->getStandardParameter()->getRedirectBackUrl() . $params);
        $redirectContainer->setErrorUrl($this->getStandardParameter()->getRedirectErrorUrl() . $params);

        return $redirectContainer;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalContainer
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return void
     */
    protected function mapBillingAddressToPersonalContainer(PersonalContainer $personalContainer, SpyPaymentPayone $paymentEntity)
    {
        $orderEntity = $paymentEntity->getSpySalesOrder();
        $billingAddressEntity = $orderEntity->getBillingAddress();
        $personalContainer->setCountry($billingAddressEntity->getCountry()->getIso2Code());
        $personalContainer->setFirstName($billingAddressEntity->getFirstName());
        $personalContainer->setLastName($billingAddressEntity->getLastName());
        $personalContainer->setSalutation($billingAddressEntity->getSalutation());
        $personalContainer->setCompany($billingAddressEntity->getCompany());
        $personalContainer->setStreet(implode(' ', [$billingAddressEntity->getAddress1(), $billingAddressEntity->getAddress2()]));
        $personalContainer->setAddressAddition($billingAddressEntity->getAddress3());
        $personalContainer->setZip($billingAddressEntity->getZipCode());
        $personalContainer->setCity($billingAddressEntity->getCity());
        $personalContainer->setEmail($billingAddressEntity->getEmail());
        $personalContainer->setTelephoneNumber($billingAddressEntity->getPhone());
        $personalContainer->setLanguage($this->getStandardParameter()->getLanguage());
        $personalContainer->setPersonalId($orderEntity->getCustomerReference());
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer $shippingContainer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $shippingAddressEntity
     *
     * @return void
     */
    protected function mapShippingAddressToShippingContainer(ShippingContainer $shippingContainer, SpySalesOrderAddress $shippingAddressEntity)
    {
        $shippingContainer->setShippingFirstName($shippingAddressEntity->getFirstName());
        $shippingContainer->setShippingLastName($shippingAddressEntity->getLastName());
        $shippingContainer->setShippingCompany($shippingAddressEntity->getCompany());
        $shippingContainer->setShippingStreet(
            implode(' ', [$shippingAddressEntity->getAddress1(), $shippingAddressEntity->getAddress2()])
        );
        $shippingContainer->setShippingZip($shippingAddressEntity->getZipCode());
        $shippingContainer->setShippingCity($shippingAddressEntity->getCity());
        $shippingContainer->setShippingCountry($shippingAddressEntity->getCountry()->getIso2Code());
    }
}
