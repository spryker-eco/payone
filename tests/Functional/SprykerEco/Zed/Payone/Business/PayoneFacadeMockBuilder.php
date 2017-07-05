<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEco\Zed\Payone\PayoneDependencyProvider;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainer;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;

class PayoneFacadeMockBuilder
{

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $adapter
     * @param \PHPUnit_Framework_TestCase $testCase
     *
     * @return \Spryker\Zed\Ratepay\Business\RatepayFacade
     */
    public function build(AdapterInterface $adapter, PHPUnit_Framework_TestCase $testCase)
    {
        // Mock business factory to override return value of createExecutionAdapter to
        // place a mocked adapter that doesn't establish an actual connection.
        $businessFactoryMock = $this->getBusinessFactoryMock($adapter, $testCase);

        // Business factory always requires a valid query container. Since we're creating
        // functional/integration tests there's no need to mock the database layer.
        $queryContainer = new PayoneQueryContainer();
        $businessFactoryMock->setQueryContainer($queryContainer);

        $container = new Container();
        $payoneDependencyProvider = new PayoneDependencyProvider();
        $payoneDependencyProvider->provideBusinessLayerDependencies($container);

        $businessFactoryMock->setContainer($container);

        // Mock the facade to override getFactory() and have it return out
        // previously created mock.
        $facade = $testCase->getMockBuilder(
            'SprykerEco\Zed\Payone\Business\PayoneFacade'
        )->setMethods(['getFactory'])->getMock();
        $facade->expects($testCase->any())
            ->method('getFactory')
            ->will($testCase->returnValue($businessFactoryMock));

        return $facade;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $adapter
     * @param \PHPUnit_Framework_TestCase $testCase
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerEco\Zed\Payone\Business\PayoneBusinessFactory
     */
    protected function getBusinessFactoryMock(AdapterInterface $adapter, PHPUnit_Framework_TestCase $testCase)
    {

        $businessFactoryMock = $testCase->getMockBuilder(
            'SprykerEco\Zed\Payone\Business\PayoneBusinessFactory'
        )->setMethods(['createExecutionAdapter'])
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $businessFactoryMock->setConfig(new PayoneConfig());
        $businessFactoryMock
            ->expects($testCase->any())
            ->method('createExecutionAdapter')
            ->will($testCase->returnValue($adapter));

        return $businessFactoryMock;
    }

}
