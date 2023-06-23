<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\II18n;
use App\Application\Configuration\Exception\AttributeException;
use App\Application\Configuration\JsonFile;

final class I18n extends JsonFile
{
    public function __construct()
    {
        parent::__construct('i18n.json', 'i18n.schema.json');
    }

    public function parse(): I18nDto
    {
        return new I18nDto($this->read());
    }
}

final class I18nDto extends FileDto implements II18n
{
    public function setCurrent(string $current): self
    {
        $locales = $this->getLocales();

        if (!in_array($current, $locales)) {
            throw new AttributeException("Locale: " . $current . " is not supported");
        }

        $this->content['current'] = $current;
        return $this;
    }

    public function getCurrent(): string
    {
        return $this->content['current'];
    }

    public function getLocales(): array
    {
        return $this->content['locales'];
    }

    public function addLocale(string $locale): self
    {
        if (!in_array($locale, $this->content['locales'])) {
            array_push($this->content['locales'], $locale);
        }

        return $this;
    }

    public function deleteLocale(string $locale): II18n
    {
        $this->content['locales'] = array_diff($this->content['locales'], [$locale]);
        return $this;
    }
};
