<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\ISession;
use App\Application\Configuration\JsonFile;

final class Session extends JsonFile
{
    public function __construct()
    {
        parent::__construct('session.json', 'session.schema.json');
    }

    public function parse(): SessionDto
    {
        return new SessionDto($this->read());
    }
}


final class SessionDto extends FileDto implements ISession
{
    public function getExpires(): int
    {
        return $this->content['expires'];
    }
    public function setExpires(int $expires): self
    {
        $this->content['expires'] = $expires;
        return $this;
    }
    
    public function getSecret(): string
    {
        return $this->content['secret'];
    }

    public function setSecret(string $secret): self
    {
        $this->content['secret'] = $secret;
        return $this;
    }
};
