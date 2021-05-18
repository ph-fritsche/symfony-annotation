<?php

namespace Pitch\Annotation\EventSubscriber;

use Pitch\Annotation\Reader;
use ReflectionMethod;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerSubscriber implements EventSubscriberInterface
{
    private Reader $reader;

    public function __construct(
        Reader $reader
    ) {
        $this->reader = $reader;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -1024],
        ];
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (\is_object($controller)) {
            $controller = [$controller, '__invoke'];
        }

        $reflMethod = new ReflectionMethod($controller[0], $controller[1]);

        $attr = [];
        foreach ($this->reader->getAnnotations($reflMethod)->all() as $a) {
            $attr[\get_class($a)][] = $a;
        }

        foreach ($attr as $class => $annotations) {
            $event->getRequest()->attributes->set('_' . $class, $annotations);
        }
    }
}
