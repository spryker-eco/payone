<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class AddressCheckResponseContainer extends AbstractResponseContainer
{
    /**
     * @var int|null
     */
    protected $secstatus;

    /**
     * @var string|null
     */
    protected $personstatus;

    /**
     * @var string|null
     */
    protected $street;

    /**
     * @var string|null
     */
    protected $streetname;

    /**
     * @var string|null
     */
    protected $streetnumber;

    /**
     * @var string|null
     */
    protected $zip;

    /**
     * @var string|null
     */
    protected $city;

    /**
     * @param string $city
     *
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $personstatus
     *
     * @return void
     */
    public function setPersonstatus(string $personstatus): void
    {
        $this->personstatus = $personstatus;
    }

    /**
     * @return string|null
     */
    public function getPersonstatus(): ?string
    {
        return $this->personstatus;
    }

    /**
     * @param int $secstatus
     *
     * @return void
     */
    public function setSecstatus(int $secstatus): void
    {
        $this->secstatus = $secstatus;
    }

    /**
     * @return int|null
     */
    public function getSecstatus(): ?int
    {
        return $this->secstatus;
    }

    /**
     * @param string $street
     *
     * @return void
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $streetname
     *
     * @return void
     */
    public function setStreetname(string $streetname): void
    {
        $this->streetname = $streetname;
    }

    /**
     * @return string|null
     */
    public function getStreetname(): ?string
    {
        return $this->streetname;
    }

    /**
     * @param string $streetnumber
     *
     * @return void
     */
    public function setStreetnumber(string $streetnumber): void
    {
        $this->streetnumber = $streetnumber;
    }

    /**
     * @return string|null
     */
    public function getStreetnumber(): ?string
    {
        return $this->streetnumber;
    }

    /**
     * @param string $zip
     *
     * @return void
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }
}
