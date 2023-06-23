<?php

namespace App\Application\Configuration\Domain;

abstract class FileDto
{
    protected array $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}
