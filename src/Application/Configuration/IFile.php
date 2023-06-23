<?php

namespace App\Application\Configuration;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\IDto;

interface IFile
{
    public function checkPermission(): bool;
    public function read(): array;
    public function validate(mixed $config, mixed $schema): array;
    public function save(FileDto $dto): self;
}
