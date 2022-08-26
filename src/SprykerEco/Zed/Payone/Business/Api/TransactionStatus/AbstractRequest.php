<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\TransactionStatus;

class AbstractRequest
{
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
            $this->set($key, $value);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $stringArray = [];
        foreach ($this->toArray() as $key => $value) {
            $stringArray[] = $key . ' = ' . $value;
        }
        $result = implode(' = ', $stringArray);

        return $result;
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
        ksort($result);

        return $result;
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
     * @param string $key
     * @param string $name
     *
     * @return bool|null
     */
    public function setValue(string $key, string $name): ?bool
    {
        return $this->set($key, $name);
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function get(string $name)
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
     * @return bool|null
     */
    public function set(string $name, $value): ?bool
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;

            return true;
        }

        return null;
    }
}
