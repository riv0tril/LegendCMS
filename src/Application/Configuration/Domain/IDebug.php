<?php 

namespace App\Application\Configuration\Domain;

interface IDebug { 
    public function setLog(bool $enabled): self;
    public function getLog(): bool;
    public function setDetails(bool $enabled): self;
    public function getDetails(): bool;
    public function setDebug(bool $enabled): self;
    public function getDebug(): bool;
    public function setLoggerPath(string $path): self;
    public function getLoggerPath(): string;
    public function setLoggerLevel(string $lvl): self;
    public function getLoggerLevel(): string;

}

