<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Request;

use SprykerEco\Client\Payone\Dependency\Client\PayoneToUtilEncodingServiceInterface;

abstract class AbstractContainer implements ContainerInterface
{
    /**
     * @var \SprykerEco\Client\Payone\Dependency\Client\PayoneToUtilEncodingServiceInterface
     */
    protected $payoneToUtilEncodingServiceBridge;

    /**
     * @param \SprykerEco\Client\Payone\Dependency\Client\PayoneToUtilEncodingServiceInterface $payoneToUtilEncodingServiceBridge
     */
    public function __construct(PayoneToUtilEncodingServiceInterface $payoneToUtilEncodingServiceBridge)
    {
        $this->payoneToUtilEncodingServiceBridge = $payoneToUtilEncodingServiceBridge;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this as $key => $value) {
            if ($value === null) {
                continue;
            }
            if ($value instanceof ContainerInterface) {
                $result = array_merge($result, $value->toArray());
            } else {
                $result[$key] = $value;
            }
        }
        ksort($result);

        return $result;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $stringArray = [];
        foreach ($this->toArray() as $key => $value) {
            $stringArray[] = $key . '=' . $value;
        }
        $result = implode('|', $stringArray);

        return $result;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return $this->payoneToUtilEncodingServiceBridge->encodeJson($this->toArray());
    }
}
