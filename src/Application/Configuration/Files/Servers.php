<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\IServers;
use App\Application\Configuration\Helper;
use App\Application\Configuration\JsonFile;

final class Servers extends JsonFile
{
    public function __construct()
    {
        parent::__construct('servers.json', 'servers.schema.json');
    }

    public function parse(): i18nDTO
    {
        return new i18nDTO(parent::read()->getContent());
    }
}

final class i18nDTO implements IServers
{
    private array $content;

    public function __construct(array &$content)
    {
        $this->content = $content;
    }

    public function getDefault(): string
    {
        return $this->content['default'];
    }
    public function setDefault(string $default): self
    {
        $this->content['default'] = $default;
        return $this;
    }

    public function getSource(): string
    {
        return $this->content['source'];
    }

    public function setSource(string $source): self
    {
        $this->content['source'] = $source;
        return $this;
    }

    public function getVersion(): string
    {
        return $this->content['version'];
    }

    public function setVersion(string $version): self
    {
        $this->content['version'] = $version;
        return $this;
    }

    public function getPool(): array
    {
        return $this->content['pool'];
    }

    public function setPool(array $pool): self
    {
        $this->content['pool'] = $pool;
        return $this;
    }

    public function removeFromPool(string $name): self
    {
        $this->content['pool'] = array_filter($this->content['pool'], function ($server) use ($name) {
            return $server['name'] !== $name;
        });
        return $this;
    }
    
    public function addToPool(array $server): self
    {
        $props = ['name', 'online', 'experience', 'drop', 'ips' => ['gameServer', 'joinServer', 'connectServer'], 'connection'];
        Helper::validateProps($props, $server);

        $exist = array_search($server['name'], array_column($this->content['pool'], 'name'));
        if ($exist !== false) {
            $this->content['pool'][] = $server;
        }
        return $this;
    }
};
