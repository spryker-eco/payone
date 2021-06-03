<?php
//phpcs:ignoreFile

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
     * @param string $encoding
     *
     * @return void
     */
    public function setEncoding($encoding)
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
    public function setKey($key)
    {
        $this->key = md5($key);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $mid
     *
     * @return void
     */
    public function setMid($mid)
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
     * @param string $aid
     *
     * @return void
     */
    public function setAid($aid)
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
    public function setMode($mode)
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
     * @param string $portalid
     *
     * @return void
     */
    public function setPortalid($portalid)
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
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $responseType
     *
     * @return void
     */
    public function setResponseType($responseType)
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
     * @param string $integrator_name
     *
     * @return void
     */
    public function setIntegratorName($integrator_name)
    {
        $this->integrator_name = $integrator_name;
    }

    /**
     * @return string
     */
    public function getIntegratorName()
    {
        return $this->integrator_name;
    }

    /**
     * set the system-version
     *
     * @param string $integrator_version
     *
     * @return void
     */
    public function setIntegratorVersion($integrator_version)
    {
        $this->integrator_version = $integrator_version;
    }

    /**
     * @return string
     */
    public function getIntegratorVersion()
    {
        return $this->integrator_version;
    }

    /**
     * set the name of the solution-partner (company)
     *
     * @param string $solution_name
     *
     * @return void
     */
    public function setSolutionName($solution_name)
    {
        $this->solution_name = $solution_name;
    }

    /**
     * @return string
     */
    public function getSolutionName()
    {
        return $this->solution_name;
    }

    /**
     * set the version of the solution-partner's app / extension / plugin / etc..
     *
     * @param string $solution_version
     *
     * @return void
     */
    public function setSolutionVersion($solution_version)
    {
        $this->solution_version = $solution_version;
    }

    /**
     * @return string
     */
    public function getSolutionVersion()
    {
        return $this->solution_version;
    }

    /**
     * @param string $hash
     *
     * @return void
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $language
     *
     * @return void
     */
    public function setLanguage($language)
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
