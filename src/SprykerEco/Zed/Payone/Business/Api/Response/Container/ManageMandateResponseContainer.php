<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class ManageMandateResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected $mandate_identification;

    /**
     * @var string
     */
    protected $mandate_status;

    /**
     * @var string
     */
    protected $mandate_text;

    /**
     * @var string
     */
    protected $creditor_identifier;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $bic;

    /**
     * @param string $mandate_identification
     *
     * @return void
     */
    public function setMandateIdentification(string $mandate_identification): void
    {
        $this->mandate_identification = $mandate_identification;
    }

    /**
     * @return string
     */
    public function getMandateIdentification(): string
    {
        return $this->mandate_identification;
    }

    /**
     * @param string $mandate_status
     *
     * @return void
     */
    public function setMandateStatus(string $mandate_status): void
    {
        $this->mandate_status = $mandate_status;
    }

    /**
     * @return string
     */
    public function getMandateStatus(): string
    {
        return $this->mandate_status;
    }

    /**
     * @param string $mandate_text
     *
     * @return void
     */
    public function setMandateText(string $mandate_text): void
    {
        $this->mandate_text = $mandate_text;
    }

    /**
     * @return string
     */
    public function getMandateText(): string
    {
        return $this->mandate_text;
    }

    /**
     * @param string $creditor_identifier
     *
     * @return void
     */
    public function setCreditorIdentifier(string $creditor_identifier): void
    {
        $this->creditor_identifier = $creditor_identifier;
    }

    /**
     * @return string
     */
    public function getCreditorIdentifier(): string
    {
        return $this->creditor_identifier;
    }

    /**
     * @param string $bic
     *
     * @return void
     */
    public function setBic(string $bic): void
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @param string $iban
     *
     * @return void
     */
    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }
}
