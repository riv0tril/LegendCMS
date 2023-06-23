<?php 

namespace App\Application\Configuration\Domain;

interface ISession { 
    public function getExpires(): int;
    public function setExpires(int $expires): self;
    public function getSecret(): string;
    public function setSecret(string $secret): self;
}