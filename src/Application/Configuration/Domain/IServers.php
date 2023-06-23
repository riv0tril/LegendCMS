<?php 

namespace App\Application\Configuration\Domain;

interface IServers { 
    public function getDefault(): string;
    public function setDefault(string $default): self;
    public function getSource(): string;
    public function setSource(string $source): self;
    public function getVersion(): string;
    public function setVersion(string $version): self;
    public function getPool(): array;
    public function setPool(array $pool): self;
    public function removeFromPool(string $name): self;
    public function addToPool(array $server): self;
    public function updateInPool(string $name, array $server): self;
    public function getFromPool(string $server): array;
}