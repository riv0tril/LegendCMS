<?php 

namespace App\Application\Configuration\Domain;

interface IDebugLogger extends IArrayable { 
    public function setPath(string $path): self;
    public function getPath(): string;
    public function setLevel(string $level): self;
    public function getLevel(): string;
}
