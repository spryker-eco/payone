<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Call;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerEco\Client\Payone\ClientApi\HashGeneratorInterface;
use SprykerEco\Client\Payone\ClientApi\Request\AbstractRequest;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;

abstract class AbstractCall implements CallInterface
{
    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Client\Payone\ClientApi\HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameterTransfer
     * @param \SprykerEco\Client\Payone\ClientApi\HashGeneratorInterface $hashGenerator
     * @param \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PayoneStandardParameterTransfer $standardParameterTransfer,
        HashGeneratorInterface $hashGenerator,
        ModeDetectorInterface $modeDetector,
        UtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->standardParameter = $standardParameterTransfer;
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \SprykerEco\Client\Payone\ClientApi\Request\AbstractRequest $container
     *
     * @return void
     */
    protected function applyStandardParameter(AbstractRequest $container): void
    {
        if ($container->getPortalid() === null) {
            $container->setPortalid($this->standardParameter->getPortalId());
        }
        if ($container->getAid() === null) {
            $container->setAid($this->standardParameter->getAid());
        }
        if ($container->getMid() === null) {
            $container->setMid($this->standardParameter->getMid());
        }
        if ($container->getEncoding() === null) {
            $container->setEncoding($this->standardParameter->getEncoding());
        }
        if ($container->getMode() === null) {
            $container->setMode($this->modeDetector->getMode());
        }
        if ($container->getLanguage() === null) {
            $container->setLanguage($this->standardParameter->getLanguage());
        }
        if ($container->getResponseType() === null) {
            $container->setResponseType($this->standardParameter->getResponseType());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function getStandardParameter(): PayoneStandardParameterTransfer
    {
        return $this->standardParameter;
    }

    /**
     * @return \SprykerEco\Client\Payone\ClientApi\HashGeneratorInterface
     */
    protected function getHashGenerator(): HashGeneratorInterface
    {
        return $this->hashGenerator;
    }

    /**
     * @return \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected function getModeDetector(): ModeDetectorInterface
    {
        return $this->modeDetector;
    }
}
