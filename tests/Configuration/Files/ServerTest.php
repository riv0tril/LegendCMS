<?php

namespace Tests\Configuration\Files;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Exception\AttributeException;
use App\Application\Configuration\Files\I18n;
use App\Application\Configuration\Files\I18nDto;
use App\Application\Configuration\Files\ServerDto;
use App\Application\Configuration\Files\Servers;
use Tests\TestCase;


class ServerTest extends TestCase
{
    public function testSuccessRead(): void
    {
        $server = new Servers();
        $server->setBase(__DIR__ . '/dist/servers/');
        $server->setFile('servers.valid.json');
        $server->setSchema('schemas/servers.valid.shema.json');
        $dto = $server->parse();
        $this->assertInstanceOf(ServerDto::class, $dto);

        $this->assertEquals($dto->getDefault(), 'Legend Slow');
        $this->assertEquals($dto->getSource(), 'Louis');
        $this->assertEquals($dto->getVersion(), '6.3');
        $this->assertEquals(count($dto->getPool()), 1);

        $fromPool = $dto->getFromPool('Legend Slow');

        $this->assertEquals($fromPool['name'], 'Legend Slow');
        $this->assertEquals($fromPool['online'], 100);
        $this->assertEquals($fromPool['experience'], '100%');
        $this->assertEquals($fromPool['drop'], '100%');
        $this->assertEquals($fromPool['connection'], 'Legend Slow');
        $this->assertEquals($fromPool['ips']['gameServer'], '192.162.0.1:1000');
        $this->assertEquals($fromPool['ips']['joinServer'], '192.162.0.1:1000');
        $this->assertEquals($fromPool['ips']['connectServer'], '192.162.0.1:1000');
    }

    public function testSetDefaulShouldThrowException(): void
    {
        $server = new Servers();
        $server->setBase(__DIR__ . '/dist/servers/');
        $server->setFile('servers.valid.json');
        $server->setSchema('schemas/servers.valid.shema.json');
        $dto = $server->parse();

        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('Invalid is not a valid server');
        $dto->setDefault('Invalid');
    }

    public function testUpdatePoolShouldThrowException(): void
    {
        $server = new Servers();
        $server->setBase(__DIR__ . '/dist/servers/');
        $server->setFile('servers.valid.json');
        $server->setSchema('schemas/servers.valid.shema.json');
        $dto = $server->parse();

        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('invalid is not a valid server');
        $dto->updateInPool('invalid', ['name' => 'invalid']);
    }

    public function testGetItemPoolShouldThrowException(): void
    {
        $debug = new Servers();
        $debug->setBase(__DIR__ . '/dist/servers/');
        $debug->setFile('servers.valid.json');
        $debug->setSchema('schemas/servers.valid.shema.json');
        $dto = $debug->parse();

        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('invalid is not a valid server');
        $dto->getFromPool('invalid');
    }

    public function testAddToPoolShouldThrowException(): void
    {
        $server = new Servers();
        $server->setBase(__DIR__ . '/dist/servers/');
        $server->setFile('servers.valid.json');
        $server->setSchema('schemas/servers.valid.shema.json');
        $dto = $server->parse();

        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('Invalid object definition missing required fields');
        $dto->addToPool([
            'name' => 'test',
            'online' => 100,
            'experience' => '110%',
            'drop' => '100%',
            'ips' => [
                'gameServer' => '192.168.0.1',
                'joinServer' => '192.168.0.1',
            ],
            'connection'
        ]);
    }

    public function testRemoveCurrentItemPoolShouldThrowException(): void
    {
        $server = new Servers();
        $server->setBase(__DIR__ . '/dist/servers/');
        $server->setFile('servers.valid.json');
        $server->setSchema('schemas/servers.valid.shema.json');
        $dto = $server->parse();

        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('Cannot remove default server');
        $dto->removeFromPool('Legend Slow');
    }

    public function testRemoveCurrentItemPoolShouldSuccess(): void
    {
        $debug = new Servers();
        $debug->setBase(__DIR__ . '/dist/servers/');
        $debug->setFile('servers.valid.multipleserver.json');
        $debug->setSchema('schemas/servers.valid.shema.json');
        $dto = $debug->parse();
        $this->assertEquals(count($dto->getPool()), 2);
        $dto->removeFromPool('Remove Me!');
        $this->assertEquals(count($dto->getPool()), 1);
        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('Remove Me! is not a valid server');
        $dto->getFromPool('Remove Me!');
    }

    public function testSuccessWrite(): void
    {
        $server = new Servers();
        $server->setBase(__DIR__ . '/dist/servers/');
        $server->setFile('servers.valid.json');
        $server->setSchema('schemas/servers.valid.shema.json');
        $dto = $server->parse();
        $this->assertInstanceOf(ServerDto::class, $dto);

        $dto->setSource('TEST');
        $dto->setVersion('16.0');

        $poolItem = [
            'name' => 'TEST',
            'online' => 100,
            'experience' => '100%',
            'drop' => '100%',
            'ips' => [
                'gameServer' => '127.0.0.1:1000',
                'joinServer' => '127.0.0.1:1000',
                'connectServer' => '127.0.0.1:1000'
            ],
            'connection' => 'TEST',
        ];

        $dto->addToPool($poolItem);
        $dto->setDefault('TEST');
        $server->save($dto);

        $content = $server->read();
        $this->assertEquals($content['source'], 'TEST');
        $this->assertEquals($content['version'], '16.0');
        $this->assertEquals($content['default'], 'TEST');
        $this->assertEquals(count($content['pool']), 2);
        $this->assertEquals($content['pool'][1], $poolItem);

        // Reset
        $dto->setSource('Louis');
        $dto->setVersion('6.3');
        $dto->setDefault('Legend Slow');
        $dto->removeFromPool('TEST');
        $server->save($dto);
    }

    public function testGetContent()
    {
        $prop = $this->prophesize(FileDto::class);
        
        $prop->getContent()->willReturn(
            [
                'source' => 'Louis',
                'version' => '6.3',
                'default' => 'Legend Slow',
                'pool' => [
                    (object)[
                        'name' => 'Legend Slow',
                        'online' => 100,
                        'experience' => '100%',
                        'drop' => '100%',
                        'ips' => [
                            'gameServer' => '192.162.0.1:1000',
                            'joinServer' => '192.162.0.1:1000',
                            'connectServer' => '192.162.0.1:1000'

                        ],
                        'connection' => 'TEST',
                    ]
                ]
            ]
        )->shouldBeCalledOnce();
        $server = new Servers();
        $server->setBase(__DIR__ . '/dist/servers/');
        $server->setFile('servers.valid.json');
        $server->setSchema('schemas/servers.valid.shema.json');
        $server->save($prop->reveal());

    }
}
