<?php

namespace App\Application\Configuration\Domain;

interface ISmtp {
    public function getHost(): string;
    public function setHost(string $host): self;
    public function getPort(): int;
    public function setPort(int $port): self;
    public function getSecure(): bool;
    public function setSecure(bool $secure): self;
    public function getUserName(): string;
    public function setUserName(string $userName): self;
}