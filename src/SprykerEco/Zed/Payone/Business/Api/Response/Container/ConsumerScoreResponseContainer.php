<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class ConsumerScoreResponseContainer extends AbstractResponseContainer
{
    /**
     * @var int|null
     */
    protected $secstatus;

    /**
     * @var string|null
     */
    protected $score;

    /**
     * @var int|null
     */
    protected $scorevalue;

    /**
     * @var string|null
     */
    protected $secscore;

    /**
     * @var string|null
     */
    protected $divergence;

    /**
     * @var string|null
     */
    protected $personstatus;

    /**
     * @var string|null
     */
    protected $firstname;

    /**
     * @var string|null
     */
    protected $lastname;

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
     * @var string|null
     */
    protected $gender;

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
     * @param string $divergence
     *
     * @return void
     */
    public function setDivergence(string $divergence): void
    {
        $this->divergence = $divergence;
    }

    /**
     * @return string|null
     */
    public function getDivergence(): ?string
    {
        return $this->divergence;
    }

    /**
     * @param string $firstname
     *
     * @return void
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     *
     * @return void
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
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
     * @param string $score
     *
     * @return void
     */
    public function setScore(string $score): void
    {
        $this->score = $score;
    }

    /**
     * @return string|null
     */
    public function getScore(): ?string
    {
        return $this->score;
    }

    /**
     * @param int $scorevalue
     *
     * @return void
     */
    public function setScorevalue(int $scorevalue): void
    {
        $this->scorevalue = $scorevalue;
    }

    /**
     * @return int|null
     */
    public function getScorevalue(): ?int
    {
        return $this->scorevalue;
    }

    /**
     * @param string $secscore
     *
     * @return void
     */
    public function setSecscore(string $secscore): void
    {
        $this->secscore = $secscore;
    }

    /**
     * @return string|null
     */
    public function getSecscore(): ?string
    {
        return $this->secscore;
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

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return void
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }
}
