<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\IDebug;
use App\Application\Configuration\JsonFile;

class Debug extends JsonFile
{
    public function __construct()
    {
        parent::__construct('debug.json', 'schemas/debug.schema.json');
    }

    public function parse(): DebugDto
    {
        return new DebugDto($this->read());
    }
}

class DebugDto extends FileDto implements IDebug
{
    public function setLog(bool $enabled): self
    {
        $this->content['log'] = $enabled;
        return $this;
    }

    public function getLog(): bool
    {
        return $this->content['log'];
    }

    public function setDetails(bool $details): self
    {
        $this->content['details'] = $details;
        return $this;
    }

    public function getDetails(): bool
    {
        return $this->content['details'];
    }

    public function getLoggerPath(): string
    {
        return $this->content['logger']['path'];
    }

    public function setLoggerPath(string $path): self
    {
        $this->content['logger']['path'] = $path;
        return $this;
    }

    public function setLoggerLevel(string $lvl): self
    {
        $this->content['logger']['level'] = $lvl;
        return $this;
    }

    public function getLoggerLevel(): string
    {
        return $this->content['logger']['level'];
    }

    public function setDebug(bool $enabled): self
    {
        $this->content['debug'] = $enabled;
        return $this;
    }

    public function getDebug(): bool
    {
        return $this->content['debug'];
    }


};
