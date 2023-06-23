<?php 

namespace App\Application\Configuration\Domain;

interface ITemplate { 
    public function getName(): string;
    public function setName(string $name): self;
    public function getPath(): string;
    public function setPath(string $path): self;
    public function getCss(): array;
    public function setCss(array $css): self;
    public function getJs(): array;
    public function setJs(array $js): self;
}