<?php 

namespace App\Application\Configuration\Domain;

interface ISettings { 
    public function getGeneral(): array;
    public function setGeneral(array $general): self;
    public function getWebsite(): array;
    public function setWebsite(array $website): self;
    public function getMaintenance(): array;
    public function setMaintenance(array $maintainance): self;
}