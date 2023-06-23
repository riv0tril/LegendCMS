<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\ISmtp;
use App\Application\Configuration\JsonFile;

final class Smtp extends JsonFile
{
    public function __construct()
    {
        parent::__construct('smtp.json', 'smtp.schema.json');
    }

    public function parse(): SmtpDTO
    {
        return new SmtpDTO(parent::read()->getContent());
    }
}

final class SmtpDTO implements ISmtp
{
    private array $content;

    public function __construct(array &$content)
    {
        $this->content = $content;
    }

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
