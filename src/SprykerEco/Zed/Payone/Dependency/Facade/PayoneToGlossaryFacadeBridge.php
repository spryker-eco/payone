<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Dependency\Facade;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

class PayoneToGlossaryFacadeBridge implements PayoneToGlossaryFacadeInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface $glossaryFacade
     */
    public function __construct($glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int $idKey
     * @param array $data
     *
     * @return string
     */
    public function translateByKeyId(int $idKey, array $data = []): string
    {
        return $this->glossaryFacade->translateByKeyId($idKey, $data);
    }

    /**
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate(string $keyName, array $data = [], ?LocaleTransfer $localeTransfer = null): string
    {
        return $this->glossaryFacade->translate($keyName, $data, $localeTransfer);
    }

    /**
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslationForCurrentLocale(string $keyName, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->createTranslationForCurrentLocale($keyName, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createTranslation(string $keyName, LocaleTransfer $localeTransfer, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->createTranslation($keyName, $localeTransfer, $value, $isActive);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createAndTouchTranslation(string $keyName, LocaleTransfer $localeTransfer, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->createAndTouchTranslation($keyName, $localeTransfer, $value, $isActive);
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function createKey(string $keyName): int
    {
        return $this->glossaryFacade->createKey($keyName);
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey(string $keyName): bool
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation(string $keyName, ?LocaleTransfer $localeTransfer = null): bool
    {
        return $this->glossaryFacade->hasTranslation($keyName, $localeTransfer);
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getKeyIdentifier(string $keyName): int
    {
        return $this->glossaryFacade->getKeyIdentifier($keyName);
    }

    /**
     * @param int $idKey
     *
     * @return void
     */
    public function touchCurrentTranslationForKeyId(int $idKey): void
    {
        $this->glossaryFacade->touchCurrentTranslationForKeyId($idKey);
    }

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getOrCreateKey(string $keyName): int
    {
        return $this->glossaryFacade->getOrCreateKey($keyName);
    }

    /**
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer): bool
    {
        return $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateAndTouchTranslation(string $keyName, LocaleTransfer $localeTransfer, string $value, bool $isActive = true): TranslationTransfer
    {
        return $this->glossaryFacade->updateAndTouchTranslation($keyName, $localeTransfer, $value, $isActive);
    }
}
