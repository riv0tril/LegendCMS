<?php

declare(strict_types=1);

use App\Configuration\Files\Debug;
use App\Configuration\Files\I18N;
use App\Configuration\Files\Servers;
use App\Configuration\Files\Session;
use App\Configuration\Files\Settings;
use App\Configuration\Files\SMTP;
use App\Configuration\Files\Template;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {

    $configurations = [
        Debug::class => new Debug(),
        I18N::class => new I18N(),
        Servers::class => new Servers(),
        Session::class => new Session(),
        Settings::class => new Settings(),
        SMTP::class => new SMTP(),
        Template::class => new Template(),
    ];

    foreach ($configurations as $config) {
        $config->parse();
    }

    // Global File Configurations
    $containerBuilder->addDefinitions($configurations);
};
