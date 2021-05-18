<?php
namespace Pitch\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use LogicException;
use PHPUnit\Framework\TestCase;
use Pitch\Annotation\Fixtures\MyPropertiesAnnotation;
use Pitch\Annotation\Fixtures\MySettersAnnotation;
use ReflectionProperty;

class AbstractAnnotationTest extends TestCase
{
    public function provideAnnotations()
    {
        return [
            'With value property' => [
                new class {
                    /**
                     * @MyPropertiesAnnotation("foo")
                     */
                    public $bar = 'baz';
                },
                MyPropertiesAnnotation::class,
                ['value' => 'foo'],
            ],
            'With value setter' => [
                new class {
                    /**
                     * @MySettersAnnotation("foo")
                     */
                    public $bar = 'baz';
                },
                MySettersAnnotation::class,
                ['theValue' => '<<< foo >>>'],
            ],
            'With named property' => [
                new class {
                    /**
                     * @MyPropertiesAnnotation(id=123)
                     */
                    public $bar = 'baz';
                },
                MyPropertiesAnnotation::class,
                ['id' => 123],
            ],
            'With named setter' => [
                new class {
                    /**
                     * @MySettersAnnotation(id=123)
                     */
                    public $bar = 'baz';
                },
                MySettersAnnotation::class,
                ['theId' => 12300],
            ],
            'With invalid named property' => [
                new class {
                    /**
                     * @MyPropertiesAnnotation(xyz="foo")
                     */
                    public $bar = 'baz';
                },
                null,
                null,
                LogicException::class,
            ],
        ];
    }

    /**
     * @dataProvider provideAnnotations
     */
    public function testAnnotations(object $obj, ?string $class, ?array $props, ?string $exception = null)
    {
        $refl = new ReflectionProperty($obj, 'bar');

        if ($exception) {
            $this->expectException($exception);
        }

        $reader = new AnnotationReader();
        $annotations = $reader->getPropertyAnnotations($refl);

        $this->assertCount(1, $annotations);
        $this->assertInstanceOf($class, $annotations[0]);
        foreach($props as $propName => $propValue) {
            $this->assertSame($propValue, $annotations[0]->$propName);
        }
    }

    public function provideAttributes()
    {
        return [
            'With value property' => [
                new class
                {
                    #[MyPropertiesAnnotation("foo")]
                    public $bar = 'baz';
                },
                MyPropertiesAnnotation::class,
                ['value' => 'foo'],
            ],
            'With value setter' => [
                new class
                {
                    #[MySettersAnnotation("foo")]
                    public $bar = 'baz';
                },
                MySettersAnnotation::class,
                ['theValue' => '<<< foo >>>'],
            ],
            'With named property' => [
                new class
                {
                    #[MyPropertiesAnnotation(id: 123)]
                    public $bar = 'baz';
                },
                MyPropertiesAnnotation::class,
                ['id' => 123],
            ],
            'With named setter' => [
                new class
                {
                    #[MySettersAnnotation(id: 123)]
                    public $bar = 'baz';
                },
                MySettersAnnotation::class,
                ['theId' => 12300],
            ],
            'With invalid named property' => [
                new class
                {
                    #[MyPropertiesAnnotation(xyz: "foo")]
                    public $bar = 'baz';
                },
                null,
                null,
                LogicException::class,
            ],
            'With values array' => [
                new class
                {
                    #[MyPropertiesAnnotation(['value' => 'foo', 'id' => 123])]
                    public $bar = 'baz';
                },
                MyPropertiesAnnotation::class,
                ['value' => 'foo', 'id' => 123],
            ],
        ];
    }

    /**
     * @requires PHP >= 8
     * @dataProvider provideAttributes
     */
    public function testAttributes(object $obj, ?string $class, ?array $props, ?string $exception = null)
    {
        $refl = new ReflectionProperty($obj, 'bar');

        $attributes = $refl->getAttributes();
        $this->assertCount(1, $attributes);

        if ($exception) {
            $this->expectException($exception);
        }

        $annot = $attributes[0]->newInstance();

        $this->assertInstanceOf($class, $annot);
        foreach ($props as $propName => $propValue) {
            $this->assertSame($propValue, $annot->$propName);
        }
    }
}
