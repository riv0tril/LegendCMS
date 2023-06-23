<?php 

namespace App\Application\Configuration\Domain;

interface Ii18n { 
    public function setCurrent(string $current): self;
    public function getCurrent(): string;
    public function getLocales(): array;
    public function addLocale(string $locale): self;
    public function deleteLocale(string $locale): self;
}