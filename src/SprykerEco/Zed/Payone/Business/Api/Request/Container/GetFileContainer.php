<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class GetFileContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_GETFILE;

    /**
     * @var string|null
     */
    protected $file_reference;

    /**
     * @var string|null
     */
    protected $file_type;

    /**
     * @var string|null
     */
    protected $file_format;

    /**
     * @return string|null
     */
    public function getFileReference(): ?string
    {
        return $this->file_reference;
    }

    /**
     * @param string $fileReference
     *
     * @return void
     */
    public function setFileReference(string $fileReference): void
    {
        $this->file_reference = $fileReference;
    }

    /**
     * @return string|null
     */
    public function getFileType(): ?string
    {
        return $this->file_type;
    }

    /**
     * @param string $fileType
     *
     * @return void
     */
    public function setFileType(string $fileType): void
    {
        $this->file_type = $fileType;
    }

    /**
     * @return string|null
     */
    public function getFileFormat(): ?string
    {
        return $this->file_format;
    }

    /**
     * @param string $fileFormat
     *
     * @return void
     */
    public function setFileFormat(string $fileFormat): void
    {
        $this->file_format = $fileFormat;
    }
}
