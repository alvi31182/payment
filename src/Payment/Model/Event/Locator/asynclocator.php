<?php

declare(strict_types=1);

namespace App\Payment\Model\Event\Locator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ServiceLocator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('asyncmessage.receiver_locator', ServiceLocator::class)
        ->args([
            [],
        ])
        ->tag('container.service_locator');
};
