<?php

namespace App\Application\Configuration\Container;

use App\Application\Configuration\Domain\FileDto;
use App\Application\Configuration\Files\Debug;
use App\Application\Configuration\Files\DebugDto;
use App\Application\Configuration\Files\I18n;
use App\Application\Configuration\Files\Servers;
use App\Application\Configuration\Files\Session;
use App\Application\Configuration\Files\Smtp;
use App\Application\Configuration\Files\Template;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    public function testConfigurationContainerSetUp()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();

        foreach ([
            Debug::class,
            I18n::class,
            Servers::class,
            Session::class,
            Session::class,
            Smtp::class,
            Template::class
        ] as $config) {
            $this->assertInstanceOf(FileDto::class, $container->get($config));
        }
    }

    public function testConfigurationContainerParse()
    {
        $debugProp = $this->prophesize(Debug::class);

        $debugProp->parse()->willReturn(new DebugDto([]))->shouldBeCalledOnce();

        $app = $this->getAppInstanceWithConfiguration([ Debug::class => $debugProp->reveal()]);
        
        /** @var Container $container */
        $app->getContainer()->get(Debug::class);
        $app->getContainer()->get(Debug::class);
    }
}
