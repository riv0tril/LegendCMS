<?php

declare(strict_types=1);

use App\Application\Configuration\Files\Debug;
use App\Application\Configuration\Files\I18n;
use App\Application\Configuration\Files\Servers;
use App\Application\Configuration\Files\Session;
use App\Application\Configuration\Files\Settings;
use App\Application\Configuration\Files\Smtp;
use App\Application\Configuration\Files\Template;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {

    $configurations = [
        Debug::class => new Debug(),
        I18n::class => new I18n(),
        Servers::class => new Servers(),
        Session::class => new Session(),
        Settings::class => new Settings(),
        Smtp::class => new Smtp(),
        Template::class => new Template(),
    ];

    // Global File Configurations
    foreach ($configurations as $name => $config) {
        $containerBuilder->addDefinitions([
            $name => function() use ($config) {
                return $config->parse();
            }
        ]);
    }
};
