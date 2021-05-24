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
use SprykerEco\Zed\Payone\Business\Key\HashGenerator;

class StandartParameterMapper implements StandartParameterMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface|\SprykerEco\Zed\Payone\Business\Key\HashGenerator
     */
    protected $hashGenerator;

    /**
     * @var \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Key\HashGenerator $hashGenerator
     * @param \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
     */
    public function __construct(HashGenerator $hashGenerator, ModeDetectorInterface $modeDetector)
    {
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
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

        return $container;
    }
}
