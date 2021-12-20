<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class ConsumerScoreResponseContainer extends AbstractResponseContainer
{
    /**
     * @var int
     */
    protected $secstatus;

    /**
     * @var string
     */
    protected $score;

    /**
     * @var int
     */
    protected $scorevalue;

    /**
     * @var string
     */
    protected $secscore;

    /**
     * @var string
     */
    protected $divergence;

    /**
     * @var string
     */
    protected $personstatus;

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $streetname;

    /**
     * @var string
     */
    protected $streetnumber;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
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
     * @return string
     */
    public function getCity(): string
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
     * @return string
     */
    public function getDivergence(): string
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
     * @return string
     */
    public function getFirstname(): string
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
     * @return string
     */
    public function getLastname(): string
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
     * @return string
     */
    public function getPersonstatus(): string
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
     * @return string
     */
    public function getScore(): string
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
     * @return int
     */
    public function getScorevalue(): int
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
     * @return string
     */
    public function getSecscore(): string
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
     * @return int
     */
    public function getSecstatus(): int
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
     * @return string
     */
    public function getStreet(): string
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
     * @return string
     */
    public function getStreetname(): string
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
     * @return string
     */
    public function getStreetnumber(): string
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
     * @return string
     */
    public function getZip(): string
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
