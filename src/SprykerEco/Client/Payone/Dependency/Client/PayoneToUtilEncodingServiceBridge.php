<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\Dependency\Client;

class PayoneToUtilEncodingServiceBridge implements PayoneToUtilEncodingServiceInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct($utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encodeJson($value, $options = null, $depth = null): ?string
    {
        return $this->utilEncodingService->encodeJson($value, $options, $depth);
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return mixed|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        return $this->utilEncodingService->decodeJson($jsonValue, $assoc, $depth, $options);
    }

    /**
     * @param array $data
     * @param string $format
     *
     * @return string|null
     */
    public function encodeToFormat(array $data, string $format): ?string
    {
        return $this->utilEncodingService->encodeToFormat($data, $format);
    }

    /**
     * @param string $data
     * @param string $format
     *
     * @return array|null
     */
    public function decodeFromFormat(string $data, string $format): ?array
    {
        return $this->utilEncodingService->decodeFromFormat($data, $format);
    }
}
