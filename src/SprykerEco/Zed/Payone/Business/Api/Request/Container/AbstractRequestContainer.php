<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

abstract class AbstractRequestContainer extends AbstractContainer
{
    /**
     * @var string|null merchant id
     */
    protected $mid;

    /**
     * @var string|null
     */
    protected $aid;

    /**
     * @var string|null payment portal id
     */
    protected $portalid;

    /**
     * @var string payment portal id as md5
     */
    protected $key;

    /**
     * @var string|null version of the the payone api defaults to 3.8
     */
    protected $api_version;

    /**
     * @var string|null test or live mode
     */
    protected $mode;

    /**
     * @var string payone query name (e.g. preauthorization, authorization...)
     */
    protected $request;

    /**
     * @var string|null encoding (ISO 8859-1 or UTF-8)
     */
    protected $encoding;

    /**
     * name of the solution-partner (company)
     *
     * @var string
     */
    protected $solution_name;

    /**
     * version of the solution-partner's app / extension / plugin / etc..
     *
     * @var string
     */
    protected $solution_version;

    /**
     * system-name
     *
     * @var string
     */
    protected $integrator_name;

    /**
     * system-version
     *
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
     * @var string|null
     */
    protected $responsetype;

    /**
     * @var array
     */
    protected $it;

    /**
     * @var array
     */
    protected $id;

    /**
     * @var array
     */
    protected $pr;

    /**
     * @var array
     */
    protected $no;

    /**
     * @var array
     */
    protected $de;

    /**
     * @var array
     */
    protected $va;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $backurl;

    /**
     * @var string|null
     */
    protected $successurl;

    /**
     * @var string|null
     */
    protected $errorurl;

    /**
     * @param string $encoding
     *
     * @return $this
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
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
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $api_version
     *
     * @return $this
     */
    public function setApiVersion($api_version)
    {
        $this->api_version = $api_version;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiVersion(): ?string
    {
        return $this->api_version;
    }

    /**
     * @param string $mid
     *
     * @return $this
     */
    public function setMid($mid)
    {
        $this->mid = $mid;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMid(): ?string
    {
        return $this->mid;
    }

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
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
     * @return $this
     */
    public function setPortalid($portalid)
    {
        $this->portalid = $portalid;

        return $this;
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
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * set the system-Name
     *
     * @param string $integrator_name
     *
     * @return $this
     */
    public function setIntegratorName($integrator_name)
    {
        $this->integrator_name = $integrator_name;

        return $this;
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
     * @return $this
     */
    public function setIntegratorVersion($integrator_version)
    {
        $this->integrator_version = $integrator_version;

        return $this;
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
     * @return $this
     */
    public function setSolutionName($solution_name)
    {
        $this->solution_name = $solution_name;

        return $this;
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
     * @return $this
     */
    public function setSolutionVersion($solution_version)
    {
        $this->solution_version = $solution_version;

        return $this;
    }

    /**
     * @return string
     */
    public function getSolutionVersion()
    {
        return $this->solution_version;
    }

    /**
     * @return string|null
     */
    public function getAid(): ?string
    {
        return $this->aid;
    }

    /**
     * @param string $aid
     *
     * @return $this
     */
    public function setAid($aid)
    {
        $this->aid = $aid;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param string $hash
     *
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return string|null
     */
    public function getResponsetype(): ?string
    {
        return $this->responsetype;
    }

    /**
     * @param string $responsetype
     *
     * @return $this
     */
    public function setResponsetype($responsetype)
    {
        $this->responsetype = $responsetype;

        return $this;
    }

    /**
     * @return array
     */
    public function getIt(): array
    {
        return $this->it;
    }

    /**
     * @param array $it
     *
     * @return void
     */
    public function setIt(array $it): void
    {
        $this->it = $it;
    }

    /**
     * @return array
     */
    public function getId(): array
    {
        return $this->id;
    }

    /**
     * @param array $id
     *
     * @return void
     */
    public function setId(array $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getPr(): array
    {
        return $this->pr;
    }

    /**
     * @param array $pr
     *
     * @return void
     */
    public function setPr(array $pr): void
    {
        $this->pr = $pr;
    }

    /**
     * @return array
     */
    public function getNo(): array
    {
        return $this->no;
    }

    /**
     * @param array $no
     *
     * @return void
     */
    public function setNo(array $no): void
    {
        $this->no = $no;
    }

    /**
     * @return array
     */
    public function getDe(): array
    {
        return $this->de;
    }

    /**
     * @param array $de
     *
     * @return void
     */
    public function setDe(array $de): void
    {
        $this->de = $de;
    }

    /**
     * @return array
     */
    public function getVa(): array
    {
        return $this->va;
    }

    /**
     * @param array $va
     *
     * @return void
     */
    public function setVa(array $va): void
    {
        $this->va = $va;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->backurl;
    }

    /**
     * @param string $backUrl
     *
     * @return void
     */
    public function setBackUrl($backUrl)
    {
        $this->backurl = $backUrl;
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->successurl;
    }

    /**
     * @param string $successUrl
     *
     * @return void
     */
    public function setSuccessUrl($successUrl)
    {
        $this->successurl = $successUrl;
    }

    /**
     * @return string
     */
    public function getErrorUrl()
    {
        return $this->errorurl;
    }

    /**
     * @param string $errorUrl
     *
     * @return void
     */
    public function setErrorUrl($errorUrl)
    {
        $this->errorurl = $errorUrl;
    }
}
