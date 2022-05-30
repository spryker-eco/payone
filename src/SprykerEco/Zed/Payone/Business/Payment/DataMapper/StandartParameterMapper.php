<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface;

class StandartParameterMapper implements StandartParameterMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @var \SprykerEco\Zed\Payone\Dependency\Plugin\StandardParameterMapperPluginInterface
     */
    protected $mapperPlugins;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface $hashGenerator
     * @param \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
     * @param \SprykerEco\Zed\Payone\Dependency\Plugin\StandardParameterMapperPluginInterface[] $mapperPlugins
     */
    public function __construct(HashGeneratorInterface $hashGenerator, ModeDetectorInterface $modeDetector, array $mapperPlugins)
    {
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
        $this->mapperPlugins = $mapperPlugins;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function setStandardParameter(AbstractRequestContainer $container, PayoneStandardParameterTransfer $standardParameter): AbstractRequestContainer
    {
        $container->setApiVersion(PayoneApiConstants::API_VERSION_3_9);
        $container->setEncoding($standardParameter->getEncoding());
        $container->setKey($this->hashGenerator->hash($standardParameter->getKey()));
        $container->setMid($standardParameter->getMid());
        $container->setPortalid($standardParameter->getPortalId());
        $container->setMode($this->modeDetector->getMode());
        $container->setIntegratorName(PayoneApiConstants::INTEGRATOR_NAME_SPRYKER);
        $container->setIntegratorVersion(PayoneApiConstants::INTEGRATOR_VERSION_3_0_0);
        $container->setSolutionName(PayoneApiConstants::SOLUTION_NAME_SPRYKER);
        $container->setSolutionVersion(PayoneApiConstants::SOLUTION_VERSION_3_0_0);

        foreach ($this->mapperPlugins as $plugin) {
            $plugin->map($container, $standardParameter);
        }

        return $container;
    }
}
