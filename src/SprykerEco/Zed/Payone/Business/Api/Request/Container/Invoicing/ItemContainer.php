<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class ItemContainer extends AbstractContainer
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $pr;

    /**
     * @var int|null
     */
    protected $no;

    /**
     * @var string|null
     */
    protected $de;

    /**
     * Artikeltyp (Enum)
     *
     * @var string|null
     */
    protected $it;

    /**
     * @var float|null
     */
    protected $va;

    /**
     * DeliveryDate (YYYYMMDD)
     *
     * @var string|null
     */
    protected $sd;

    /**
     * Lieferzeitraums-Ende (YYYYMMDD)
     *
     * @var string|null
     */
    protected $ed;

    /**
     * @param int $key
     *
     * @return array
     */
    public function toArrayByKey(int $key): array
    {
        $data = [];
        if ($this->id !== null) {
            $data['id[' . $key . ']'] = $this->getId();
        }
        if ($this->pr !== null) {
            $data['pr[' . $key . ']'] = $this->getPr();
        }
        if ($this->no !== null) {
            $data['no[' . $key . ']'] = $this->getNo();
        }
        if ($this->de !== null) {
            $data['de[' . $key . ']'] = $this->getDe();
        }
        if ($this->it !== null) {
            $data['it[' . $key . ']'] = $this->getIt();
        }
        if ($this->va !== null) {
            $data['va[' . $key . ']'] = $this->getVa();
        }
        if ($this->sd !== null) {
            $data['sd[' . $key . ']'] = $this->getSd();
        }
        if ($this->ed !== null) {
            $data['ed[' . $key . ']'] = $this->getEd();
        }

        return $data;
    }

    /**
     * @param string|null $de
     *
     * @return void
     */
    public function setDe(?string $de = null): void
    {
        $this->de = $de;
    }

    /**
     * @return string|null
     */
    public function getDe(): ?string
    {
        return $this->de;
    }

    /**
     * @param string $ed
     *
     * @return void
     */
    public function setEd(string $ed): void
    {
        $this->ed = $ed;
    }

    /**
     * @return string|null
     */
    public function getEd(): ?string
    {
        return $this->ed;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param int|null $no
     *
     * @return void
     */
    public function setNo(?int $no = null): void
    {
        $this->no = $no;
    }

    /**
     * @return int|null
     */
    public function getNo(): ?int
    {
        return $this->no;
    }

    /**
     * @param int|null $pr
     *
     * @return void
     */
    public function setPr(?int $pr = null): void
    {
        $this->pr = $pr;
    }

    /**
     * @return int|null
     */
    public function getPr(): ?int
    {
        return $this->pr;
    }

    /**
     * @param string $sd
     *
     * @return void
     */
    public function setSd(string $sd): void
    {
        $this->sd = $sd;
    }

    /**
     * @return string|null
     */
    public function getSd(): ?string
    {
        return $this->sd;
    }

    /**
     * @param float|null $va
     *
     * @return void
     */
    public function setVa(?float $va = null): void
    {
        $this->va = $va;
    }

    /**
     * @return float|null
     */
    public function getVa(): ?float
    {
        return $this->va;
    }

    /**
     * @param string $it
     *
     * @return void
     */
    public function setIt(string $it): void
    {
        $this->it = $it;
    }

    /**
     * @return string|null
     */
    public function getIt(): ?string
    {
        return $this->it;
    }
}
