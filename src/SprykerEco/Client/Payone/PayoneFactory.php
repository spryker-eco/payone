<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\Config\Config;
use SprykerEco\Client\Payone\ClientApi\Call\CreditCardCheck;
use SprykerEco\Client\Payone\ClientApi\Call\CreditCardCheckInterface;
use SprykerEco\Client\Payone\ClientApi\HashGenerator;
use SprykerEco\Client\Payone\ClientApi\HashGeneratorInterface;
use SprykerEco\Client\Payone\ClientApi\HashProvider;
use SprykerEco\Client\Payone\Zed\PayoneStub;
use SprykerEco\Client\Payone\Zed\PayoneStubInterface;
use SprykerEco\Shared\Payone\Dependency\HashInterface;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\Business\Mode\ModeDetector;
use SprykerEco\Zed\Payone\PayoneConfig;

class PayoneFactory extends AbstractFactory
{
    /**
     * @param array $defaults
     *
     * @return \SprykerEco\Client\Payone\ClientApi\Call\CreditCardCheckInterface
     */
    public function createCreditCardCheckCall(array $defaults): CreditCardCheckInterface
    {
        return new CreditCardCheck(
            $this->createStandardParameter($defaults),
            $this->createHashGenerator(),
            $this->createModeDetector(),
            $this->createUtilEncodingService(),
        );
    }

    /**
     * @return \SprykerEco\Shared\Payone\Dependency\HashInterface
     */
    protected function createHashProvider(): HashInterface
    {
        return new HashProvider();
    }

    /**
     * @return \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected function createModeDetector(): ModeDetectorInterface
    {
        return new ModeDetector($this->createBundleConfig());
    }

    /**
     * @return \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected function createBundleConfig(): PayoneConfig
    {
        return new PayoneConfig();
    }

    /**
     * @return \SprykerEco\Client\Payone\ClientApi\HashGeneratorInterface
     */
    protected function createHashGenerator(): HashGeneratorInterface
    {
        return new HashGenerator(
            $this->createHashProvider(),
        );
    }

    /**
     * @param array $defaults
     *
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function createStandardParameter(array $defaults): PayoneStandardParameterTransfer
    {
        $standardParameterTransfer = new PayoneStandardParameterTransfer();
        $standardParameterTransfer->fromArray($defaults);

        $payoneConfig = Config::get(PayoneConstants::PAYONE);
        $standardParameterTransfer->setAid($payoneConfig[PayoneConstants::PAYONE_CREDENTIALS_AID]);
        $standardParameterTransfer->setMid($payoneConfig[PayoneConstants::PAYONE_CREDENTIALS_MID]);
        $standardParameterTransfer->setPortalId($payoneConfig[PayoneConstants::PAYONE_CREDENTIALS_PORTAL_ID]);
        $standardParameterTransfer->setKey($payoneConfig[PayoneConstants::PAYONE_CREDENTIALS_KEY]);
        $standardParameterTransfer->setEncoding($payoneConfig[PayoneConstants::PAYONE_CREDENTIALS_ENCODING]);
        $standardParameterTransfer->setResponseType(PayoneApiConstants::RESPONSE_TYPE_JSON);

        return $standardParameterTransfer;
    }

    /**
     * @return \SprykerEco\Client\Payone\Zed\PayoneStubInterface
     */
    public function createZedStub(): PayoneStubInterface
    {
        $zedStub = $this->getProvidedDependency(PayoneDependencyProvider::SERVICE_ZED);

        return new PayoneStub($zedStub);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function createUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
