<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\ISettings;
use App\Application\Configuration\Helper;
use App\Application\Configuration\JsonFile;

final class Settings extends JsonFile
{
    public function __construct()
    {
        parent::__construct('settings.json', 'schemas/settings.schema.json');
    }

    public function parse(): SettingsDTO
    {
        return new SettingsDto($this->read());
    }
}

final class SettingsDto extends FileDto implements ISettings
{
    public function getGeneral(): array
    {
        return $this->content['general'];
    }
    
    public function setGeneral(array $general): self
    {
        $props = ['baseUrl', 'admincp', 'httpVersion'];
        Helper::validateProps($props, $general);
        $this->content['general'] = $general;
        return $this;
    }

    public function getWebsite(): array
    {
        return $this->content['website'];
    }

    public function setWebsite(array $website): self
    {
        $props = ['title', 'name', 'meta' => ['robots', 'charset', 'keywords', 'description']];
        Helper::validateProps($props, $website);
        $this->content['website'] = $website;
        return $this;
    }

    public function getMaintenance(): array
    {
        return $this->content['maintenance'];
    }

    public function setMaintenance(array $maintainance): self
    {
        $props = ['enabled', 'message', 'end', 'start'];
        Helper::validateProps($props, $maintainance);
        $this->content['maintenance'] = $maintainance;
        return $this;
    }
};
