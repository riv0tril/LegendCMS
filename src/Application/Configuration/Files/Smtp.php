<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\ISmtp;
use App\Application\Configuration\JsonFile;

final class Smtp extends JsonFile
{
    public function __construct()
    {
        parent::__construct('smtp.json', 'schemas/smtp.schema.json');
    }

    public function parse(): SmtpDto
    {
        return new SmtpDto($this->read());
    }
}

final class SmtpDto extends FileDto implements ISmtp
{
    public function getHost(): string
    {
        return $this->content['host'];
    }
    
    public function setHost(string $host): self
    {
        $this->content['host'] = $host;
        return $this;
    }

    public function getPort(): int
    {
        return $this->content['port'];
    }

    public function setPort(int $port): self
    {
        $this->content['port'] = $port;
        return $this;
    }

    public function getSecure(): bool
    {
        return $this->content['secure'];
    }

    public function setSecure(bool $secure): self
    {
        $this->content['secure'] = $secure;
        return $this;
    }

    public function getUserName(): string
    {
        return $this->content['userName'];
    }

    public function setUserName(string $userName): self
    {
        $this->content['userName'] = $userName;
        return $this;
    }
};
