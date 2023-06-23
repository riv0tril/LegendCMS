<?php

namespace Tests\Configuration;

use App\Application\Configuration\Exception\JsonFileException;
use App\Application\Configuration\JsonFile;
use stdClass;
use Tests\TestCase;

class JsonFileTest extends TestCase
{
    private $instance = null;

    public function setUp(): void
    {
        $this->instance = new class extends JsonFile
        {

            public function __construct()
            {
                parent::__construct('dummy.json', 'schemas/dummy.schema.json');
            }

            public function parse(): mixed
            {
                return new stdClass($this->read());
            }
        };
    }

    public function testCheckPermissionInvalidPath(): void
    {
        $this->instance->setBase('');
        $this->expectException(JsonFileException::class);
        $this->expectExceptionMessage("Configuration file: dummy.json or schema file: dummy.schema.json does not exist");
        $this->instance->checkPermission();
    }

    public function testCheckePermissionMissingFile(): void
    {
        $this->instance->setBase('');
        $this->instance->setFile('missing.json');
        $this->expectException(JsonFileException::class);
        $this->expectExceptionMessage("Configuration file: missing.json or schema file: dummy.schema.json does not exist");
        $this->instance->checkPermission();
    }

    public function testCheckePermissionMissingSchema(): void
    {
        $this->instance->setSchema('/schemas/missing.schema.json');
        $this->expectException(JsonFileException::class);
        $this->expectExceptionMessage("Configuration file: dummy.json or schema file: missing.schema.json does not exist");
        $this->instance->checkPermission();
    }

    public function testCheckPermissionValid(): void
    {
        $this->instance->setBase(__DIR__ . '/Files/dist/dummy/');
        $this->assertTrue($this->instance->checkPermission());
    }

    public function testReadInvalidFormat(): void
    {
        $this->instance->setBase(__DIR__ . '/Files/dist/dummy/');
        $this->instance->setFile('dummy.invalid.json');
        $this->expectException(JsonFileException::class);
        $this->expectExceptionMessage("Schema validation fail for file: dummy.invalid.json  - The property debug is required");
        $this->instance->read();
    }

    public function testReadInvalidNotJsonFile(): void
    {
        $this->instance->setBase(__DIR__ . '/Files/dist/dummy/');
        $this->instance->setFile('dummy.txt');
        $this->expectException(JsonFileException::class);
        $this->expectExceptionMessage("Invalid configuration file: dummy.txt is not a valid JSON file");
        $this->instance->read();
    }
}
