<?php
namespace Pitch\Annotation\Resources\config;

use Pitch\Annotation\EventSubscriber\ControllerSubscriber;
use Pitch\Annotation\Reader;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
            ->autowire()
        ->set(Reader::class)
        ->set(ControllerSubscriber::class)
            ->tag('kernel.event_subscriber')
    ;
};
