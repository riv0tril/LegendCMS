<?php

namespace App\Application\Configuration;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Domain\IDto;
use App\Application\Configuration\Exception\JsonFileException;
use JsonSchema\Validator;

abstract class JsonFile implements IFile
{
    /*
    * @var $file string
    * @description The file name of the configuration file
    */
    private string $file;

    /*
    * @var $schema string
    * @description The file name of the schema file
    */
    private string $schema;
    private string $base;

    protected array $content;


    public function __construct(string $file, string $schema)
    {
        $this->file = $file;
        $this->schema = $schema;
        $this->content = [];
        $this->base = __DIR__ . '/../../../config/';
    }

    public function checkPermission(): bool
    {
        if (!file_exists($this->getFilePath()) || !file_exists($this->getSchemaPath())) {
            throw new JsonFileException("Configuration file: " . basename($this->getFilePath()) . " or schema file: " . basename($this->getSchemaPath()) . " does not exist");
        }

        if (!is_readable($this->getFilePath()) || !is_readable($this->getSchemaPath())) {
            throw new JsonFileException("Configuration file: " . basename($this->getFilePath()) . " or schema file: " . basename($this->getSchemaPath()) . " is not readable");
        }

        if (!is_writable($this->getFilePath())) {
            throw new JsonFileException("Configuration file: " . basename($this->getFilePath()) . " is not writable");
        }

        return true;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;
        return $this;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function setSchema(string $schema): self
    {
        $this->schema = $schema;
        return $this;
    }

    public function getFilePath(): string
    {
        return $this->base . $this->file;
    }

    public function getSchemaPath(): string
    {
        return $this->base . $this->schema;
    }

    public function read(): array
    {
        $this->checkPermission();
        $config = json_decode(file_get_contents($this->getFilePath()));
        $schema = json_decode(file_get_contents($this->getSchemaPath()));
        $this->validate($config, $schema);;
        return json_decode(json_encode($config), true);
    }


    public function validate(mixed $config, mixed $schema): array
    {
        if ($config == null || $schema == null) {
            throw new JsonFileException("Invalid configuration file: " . basename($this->getFilePath()) . " is not a valid JSON file");
        }

        $validator = new Validator();
        $validator->validate($config, $schema, \JsonSchema\Constraints\Constraint::CHECK_MODE_APPLY_DEFAULTS);

        if (!$validator->isValid()) {
            $error = $validator->getErrors()[0];
            throw new JsonFileException("Schema validation fail for file: " . basename($this->getFilePath()) . " " . ($error ? " - " . $error['message'] : ''));
        }

        return json_decode(json_encode($config), true);
    }

    public function save(FileDto $dto): self
    {
        $this->checkPermission();
        $schema = json_decode(file_get_contents($this->getSchemaPath()));
        $content =  json_decode(json_encode($dto->getContent()));
        $this->validate($content, $schema);
        file_put_contents($this->getFilePath(), json_encode($content, JSON_PRETTY_PRINT));
        return $this;
    }

    public abstract function parse(): mixed;
}
