<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Request;

abstract class AbstractRequest extends AbstractContainer
{
    /**
     * @var string|null
     */
    protected $mid;

    /**
     * @var string|null
     */
    protected $aid;

    /**
     * @var string|null
     */
    protected $portalid;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string|null
     */
    protected $mode;

    /**
     * @var string
     */
    protected $request;

    /**
     * @var string|null
     */
    protected $responsetype;

    /**
     * @var string|null
     */
    protected $encoding;

    /**
     * @var string
     */
    protected $solution_name;

    /**
     * @var string
     */
    protected $solution_version;

    /**
     * @var string
     */
    protected $integrator_name;

    /**
     * @var string
     */
    protected $integrator_version;

    /**
     * @var string|null
     */
    protected $language;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @param string|null $encoding
     *
     * @return void
     */
    public function setEncoding(?string $encoding = null): void
    {
        $this->encoding = $encoding;
    }

    /**
     * @return string|null
     */
    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function setKey(string $key): void
    {
        $this->key = md5($key);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string|null $mid
     *
     * @return void
     */
    public function setMid(?string $mid = null): void
    {
        $this->mid = $mid;
    }

    /**
     * @return string|null
     */
    public function getMid(): ?string
    {
        return $this->mid;
    }

    /**
     * @param string|null $aid
     *
     * @return void
     */
    public function setAid(?string $aid = null): void
    {
        $this->aid = $aid;
    }

    /**
     * @return string|null
     */
    public function getAid(): ?string
    {
        return $this->aid;
    }

    /**
     * @param string $mode
     *
     * @return void
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @return string|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @param string|null $portalid
     *
     * @return void
     */
    public function setPortalid(?string $portalid = null): void
    {
        $this->portalid = $portalid;
    }

    /**
     * @return string|null
     */
    public function getPortalid(): ?string
    {
        return $this->portalid;
    }

    /**
     * @param string $request
     *
     * @return void
     */
    public function setRequest(string $request): void
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * @param string|null $responseType
     *
     * @return void
     */
    public function setResponseType(?string $responseType): void
    {
        $this->responsetype = $responseType;
    }

    /**
     * @return string|null
     */
    public function getResponseType(): ?string
    {
        return $this->responsetype;
    }

    /**
     * set the system-Name
     *
     * @param string $integratorName
     *
     * @return void
     */
    public function setIntegratorName(string $integratorName): void
    {
        $this->integrator_name = $integratorName;
    }

    /**
     * @return string
     */
    public function getIntegratorName(): string
    {
        return $this->integrator_name;
    }

    /**
     * set the system-version
     *
     * @param string $integratorVersion
     *
     * @return void
     */
    public function setIntegratorVersion(string $integratorVersion): void
    {
        $this->integrator_version = $integratorVersion;
    }

    /**
     * @return string
     */
    public function getIntegratorVersion(): string
    {
        return $this->integrator_version;
    }

    /**
     * set the name of the solution-partner (company)
     *
     * @param string $solutionName
     *
     * @return void
     */
    public function setSolutionName(string $solutionName): void
    {
        $this->solution_name = $solutionName;
    }

    /**
     * @return string
     */
    public function getSolutionName(): string
    {
        return $this->solution_name;
    }

    /**
     * set the version of the solution-partner's app / extension / plugin / etc..
     *
     * @param string $solutionVersion
     *
     * @return void
     */
    public function setSolutionVersion(string $solutionVersion): void
    {
        $this->solution_version = $solutionVersion;
    }

    /**
     * @return string
     */
    public function getSolutionVersion(): string
    {
        return $this->solution_version;
    }

    /**
     * @param string $hash
     *
     * @return void
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string|null $language
     *
     * @return void
     */
    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }
}
