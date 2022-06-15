<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

abstract class AbstractResponseContainer
{
    /**
     * @var string|null
     */
    protected $status;

    /**
     * @var string|null
     */
    protected $rawResponse;

    /**
     * @var string|null
     */
    protected $errorcode;

    /**
     * @var string|null
     */
    protected $errormessage;

    /**
     * @var string|null
     */
    protected $customermessage;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (count($params) > 0) {
            $this->init($params);
        }
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function init(array $data = []): void
    {
        foreach ($data as $key => $value) {
            $key = $this->getPreparedKey($key);
            $method = 'set' . str_replace(' ', '', $key);

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this as $key => $data) {
            if ($data === null) {
                continue;
            } else {
                $result[$key] = $data;
            }
        }

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
     * @param string $status
     *
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $customermessage
     *
     * @return void
     */
    public function setCustomermessage(string $customermessage): void
    {
        $this->customermessage = $customermessage;
    }

    /**
     * @return string|null
     */
    public function getCustomermessage(): ?string
    {
        return $this->customermessage;
    }

    /**
     * @param string $errorcode
     *
     * @return void
     */
    public function setErrorcode(string $errorcode): void
    {
        $this->errorcode = $errorcode;
    }

    /**
     * @return string|null
     */
    public function getErrorcode(): ?string
    {
        return $this->errorcode;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        if ($this->errorcode === null) {
            return false;
        }

        return true;
    }

    /**
     * @param string $errormessage
     *
     * @return void
     */
    public function setErrormessage(string $errormessage): void
    {
        $this->errormessage = $errormessage;
    }

    /**
     * @return string|null
     */
    public function getErrormessage(): ?string
    {
        return $this->errormessage;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getValue(string $key)
    {
        return $this->get($key);
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function setValue(string $name, $value): bool
    {
        return $this->set($name, $value);
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    protected function get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return bool
     */
    protected function set(string $name, $value): bool
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;

            return true;
        }

        return false;
    }

    /**
     * @param string $rawResponse
     *
     * @return void
     */
    public function setRawResponse(string $rawResponse): void
    {
        $this->rawResponse = $rawResponse;
    }

    /**
     * @return string|null
     */
    public function getRawResponse(): ?string
    {
        return $this->rawResponse;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPreparedKey(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }
}
