<?php

namespace Tests\Configuration\Files;

use App\Application\Configuration\Domain\IDebugLogger;
use App\Application\Configuration\Exception\JsonFileException;
use App\Application\Configuration\Files\Debug;
use App\Application\Configuration\Files\DebugDTO;
use Tests\TestCase;


class DebugTest extends TestCase
{
    public function testReadValidFormat(): void
    {
        $debug = new Debug();
        $debug->setBase(__DIR__ . '/dist/debug/');
        $debug->setFile('debug.default.json');
        $debug->setSchema('schemas/debug.default.schema.json');
        $dto = $debug->parse();
        $this->assertInstanceOf(DebugDTO::class, $dto);

        $this->assertEquals($dto->getLog(), false);
        $this->assertEquals($dto->getDebug(), false);
        $this->assertEquals($dto->getDetails(), false);
    
        $this->assertEquals($dto->getLoggerLevel(), 'INFO');
        $this->assertEquals($dto->getLoggerPath(), 'app.log');
    }

    public function testWriteValidFormat(): void
    {
        $debug = new Debug();
        $debug->setBase(__DIR__ . '/dist/debug/');
        $debug->setFile('debug.default.json');
        $debug->setSchema('schemas/debug.default.schema.json');
        $dto = $debug->parse();

        $dto->setLog(true)->getLog();
        $dto->setDetails(true)->getDetails();
        $dto->setDebug(true)->getDebug();
        $dto->setLoggerLevel('DEBUG');
        $dto->setLoggerPath('test.log');

        $debug->save($dto);
        $refresh = $debug->read();

        $aLog = $refresh['log'];
        $aDet = $refresh['details'];
        $aDbg = $refresh['debug'];
        $aLvl = $refresh['logger']['level'];
        $aPat = $refresh['logger']['path'];

        $this->assertEquals(true, $aLog);
        $this->assertEquals(true, $aDet);
        $this->assertEquals(true, $aDbg);
        $this->assertEquals('DEBUG', $aLvl);
        $this->assertEquals('test.log', $aPat);
        $this->assertEquals('test.log', 'false');

        //revert to original state
        $dto->setLog(false);
        $dto->setDetails(false);
        $dto->setDebug(false);
        $dto->setLoggerLevel('INFO');
        $dto->setLoggerPath('app.log');
        $debug->save($dto);
    }
}
