<?php
// phpcs:disable Spryker.ControlStructures.DisallowCloakingCheck.InvalidIsset

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
     * @var string
     */
    protected $it;

    /**
     * @var float|null
     */
    protected $va;

    /**
     * DeliveryDate (YYYYMMDD)
     *
     * @var string
     */
    protected $sd;

    /**
     * Lieferzeitraums-Ende (YYYYMMDD)
     *
     * @var string
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
        if (isset($this->id)) {
            $data['id[' . $key . ']'] = $this->getId();
        }
        if (isset($this->pr)) {
            $data['pr[' . $key . ']'] = $this->getPr();
        }
        if (isset($this->no)) {
            $data['no[' . $key . ']'] = $this->getNo();
        }
        if (isset($this->de)) {
            $data['de[' . $key . ']'] = $this->getDe();
        }
        if (isset($this->it)) {
            $data['it[' . $key . ']'] = $this->getIt();
        }
        if (isset($this->va)) {
            $data['va[' . $key . ']'] = $this->getVa();
        }
        if (isset($this->sd)) {
            $data['sd[' . $key . ']'] = $this->getSd();
        }
        if (isset($this->ed)) {
            $data['ed[' . $key . ']'] = $this->getEd();
        }

        return $data;
    }

    /**
     * @param string|null $de
     *
     * @return void
     */
    public function setDe(?string $de): void
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
     * @return string
     */
    public function getEd(): string
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
    public function setNo(?int $no): void
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
    public function setPr(?int $pr): void
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
     * @return string
     */
    public function getSd(): string
    {
        return $this->sd;
    }

    /**
     * @param float|null $va
     *
     * @return void
     */
    public function setVa(?float $va): void
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
     * @return string
     */
    public function getIt(): string
    {
        return $this->it;
    }
}
