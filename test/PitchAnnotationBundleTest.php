<?php
namespace Pitch\Annotation;

use Pitch\Annotation\Fixtures\MyPropertiesAnnotation;
use Pitch\Annotation\Fixtures\MySettersAnnotation;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;

class PitchAnnotationBundleTest extends KernelTestCase
{
    private EventDispatcher $dispatcher;

    protected static function getKernelClass()
    {
        return get_class(new class('', true) extends Kernel
        {
            public function getProjectDir()
            {
                return $this->dir ??= sys_get_temp_dir() . '/' . spl_object_id($this) . '/';
            }

            public function registerBundles(): iterable
            {
                return [
                    new FrameworkBundle(),
                    new PitchAnnotationBundle(),
                ];
            }

            public function registerContainerConfiguration(LoaderInterface $loader)
            {
            }
        });
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->dispatcher = self::$kernel->getContainer()->get('event_dispatcher');
    }

    public function testAddRequestAttributes(): void
    {
        $request = new Request();
        $controller = new class {
            /**
             * @MyPropertiesAnnotation("foo")
             * @MySettersAnnotation("bar")
             */
            #[MyPropertiesAnnotation("baz")]
            public function __invoke()
            {
            }
        };

        $this->dispatcher->dispatch(
            new ControllerEvent(self::$kernel, $controller, $request, null),
            KernelEvents::CONTROLLER,
        );

        $this->assertEquals(
            PHP_MAJOR_VERSION >= 8
                ? [
                    new MyPropertiesAnnotation("foo"),
                    new MyPropertiesAnnotation("baz"),
                ]
                : [
                    new MyPropertiesAnnotation("baz"),
                ],
            $request->attributes->get('_' . MyPropertiesAnnotation::class),
        );

        $this->assertEquals(
            [
                new MySettersAnnotation("bar"),
            ],
            $request->attributes->get('_' . MySettersAnnotation::class),
        );
    }
}
