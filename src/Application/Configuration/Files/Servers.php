<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\IServers;
use App\Application\Configuration\Exception\AttributeException;
use App\Application\Configuration\Helper;
use App\Application\Configuration\JsonFile;

class Servers extends JsonFile
{
    public function __construct()
    {
        parent::__construct('servers.json', 'schemas/servers.schema.json');
    }

    public function parse(): ServerDto
    {
        return new ServerDto($this->read());
    }
}

class ServerDto extends FileDto implements IServers
{
    public function getDefault(): string
    {
        return $this->content['default'];
    }
    public function setDefault(string $default): self
    {
        $pool = $this->getPool();
        $found = false;
        foreach ($pool as $server) {
            if ($server['name'] === $default) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new AttributeException($default . " is not a valid server");
        }

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
        if ($name === $this->getDefault()) {
            throw new AttributeException("Cannot remove default server");
        }

        $this->content['pool'] = array_filter($this->content['pool'], function ($server) use ($name) {
            return $server['name'] !== $name;
        });
        return $this;
    }

    public function addToPool(array $server): self
    {
        $props = ['name', 'online', 'experience', 'drop', 'ips' => ['gameServer', 'joinServer', 'connectServer'], 'connection'];
        $result = Helper::validateProps($props, $server);

        if (!$result) {
            throw new AttributeException("Invalid object definition missing required fields");
        }

        $exist = array_search($server['name'], array_column($this->content['pool'], 'name'));
        if ($exist === false) {
            $this->content['pool'][] = $server;
        }
        return $this;
    }

    public function updateInPool(string $name, array $server): self
    {
        $exist = array_search($name, array_column($this->content['pool'], 'name'));
        if ($exist === false) {
            throw new AttributeException($name . " is not a valid server");
        }

        $props = ['name', 'online', 'experience', 'drop', 'ips' => ['gameServer', 'joinServer', 'connectServer'], 'connection'];
        Helper::validateProps($props, $server);
        $this->content['pool'][$exist] = $server;

        return $this;
    }

    public function getFromPool(string $server): array
    {
        $idx = array_search($server, array_column($this->content['pool'], 'name'));
        if ($idx === false) {
            throw new AttributeException($server . " is not a valid server");
        }
        return $this->content['pool'][$idx];
    }
};
