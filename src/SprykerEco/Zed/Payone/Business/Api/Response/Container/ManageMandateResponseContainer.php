<?php
//phpcs:ignoreFile

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
    public function setMandateIdentification($mandate_identification)
    {
        $this->mandate_identification = $mandate_identification;
    }

    /**
     * @return string
     */
    public function getMandateIdentification()
    {
        return $this->mandate_identification;
    }

    /**
     * @param string $mandate_status
     *
     * @return void
     */
    public function setMandateStatus($mandate_status)
    {
        $this->mandate_status = $mandate_status;
    }

    /**
     * @return string
     */
    public function getMandateStatus()
    {
        return $this->mandate_status;
    }

    /**
     * @param string $mandate_text
     *
     * @return void
     */
    public function setMandateText($mandate_text)
    {
        $this->mandate_text = $mandate_text;
    }

    /**
     * @return string
     */
    public function getMandateText()
    {
        return $this->mandate_text;
    }

    /**
     * @param string $creditor_identifier
     *
     * @return void
     */
    public function setCreditorIdentifier($creditor_identifier)
    {
        $this->creditor_identifier = $creditor_identifier;
    }

    /**
     * @return string
     */
    public function getCreditorIdentifier()
    {
        return $this->creditor_identifier;
    }

    /**
     * @param string $bic
     *
     * @return void
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $iban
     *
     * @return void
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }
}
