<?php

namespace App\Application\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\ITemplate;
use App\Application\Configuration\JsonFile;

final class Template extends JsonFile
{
    public function __construct()
    {
        parent::__construct('template.json', 'schemas/template.schema.json');
    }

    public function parse(): TemplateDto
    {
        return new TemplateDto($this->read());
    }
}

final class TemplateDto extends FileDto implements ITemplate
{
    public function getName(): string
    {
        return $this->content['name'];
    }

    public function setName(string $name): self
    {
        $this->content['name'] = $name;
        return $this;
    }
    public function getPath(): string
    {
        return $this->content['path'];
    }

    public function setPath(string $path): self
    {
        $this->content['path'] = $path;
        return $this;
    }

    public function getCss(): array
    {
        return $this->content['css'];
    }

    public function setCss(array $css): self
    {
        $this->content['css'] = $css;
        return $this;
    }

    public function getJs(): array
    {
        return $this->content['js'];
    }
    
    public function setJs(array $js): self
    {
        $this->content['js'] = $js;
        return $this;
    }
};
