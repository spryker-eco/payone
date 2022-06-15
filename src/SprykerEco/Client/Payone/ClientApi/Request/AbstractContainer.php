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
    protected $utilEncodingService;

    /**
     * @param \SprykerEco\Client\Payone\Dependency\Client\PayoneToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PayoneToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
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
        return $this->utilEncodingService->encodeJson($this->toArray());
    }
}
