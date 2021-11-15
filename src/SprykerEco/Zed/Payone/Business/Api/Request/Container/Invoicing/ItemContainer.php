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
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $pr;

    /**
     * @var int
     */
    protected $no;

    /**
     * @var string
     */
    protected $de;

    /**
     * Artikeltyp (Enum)
     *
     * @var string
     */
    protected $it;

    /**
     * @var int
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
    public function toArrayByKey($key): array
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
     * @param string $de
     *
     * @return void
     */
    public function setDe($de): void
    {
        $this->de = $de;
    }

    /**
     * @return string
     */
    public function getDe(): string
    {
        return $this->de;
    }

    /**
     * @param string $ed
     *
     * @return void
     */
    public function setEd($ed): void
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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param int $no
     *
     * @return void
     */
    public function setNo($no): void
    {
        $this->no = $no;
    }

    /**
     * @return int
     */
    public function getNo(): int
    {
        return $this->no;
    }

    /**
     * @param int $pr
     *
     * @return void
     */
    public function setPr($pr): void
    {
        $this->pr = $pr;
    }

    /**
     * @return int
     */
    public function getPr(): int
    {
        return $this->pr;
    }

    /**
     * @param string $sd
     *
     * @return void
     */
    public function setSd($sd): void
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
     * @param int $va
     *
     * @return void
     */
    public function setVa($va): void
    {
        $this->va = $va;
    }

    /**
     * @return int
     */
    public function getVa(): int
    {
        return $this->va;
    }

    /**
     * @param string $it
     *
     * @return void
     */
    public function setIt($it): void
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
