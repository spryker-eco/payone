<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Call;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface;

abstract class AbstractCall
{
    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameterTransfer
     * @param \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface $hashGenerator
     * @param \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
     */
    public function __construct(
        PayoneStandardParameterTransfer $standardParameterTransfer,
        HashGeneratorInterface $hashGenerator,
        ModeDetectorInterface $modeDetector
    ) {
        $this->standardParameter = $standardParameterTransfer;
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return void
     */
    protected function applyStandardParameter(AbstractRequestContainer $container): void
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
        if ($container->getApiVersion() === null) {
            $container->setApiVersion($this->standardParameter->getApiVersion());
        }
        if ($container->getResponsetype() === null) {
            $container->setResponsetype($this->standardParameter->getResponseType());
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
     * @return \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface
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
