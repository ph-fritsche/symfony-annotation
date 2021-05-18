<?php
namespace Pitch\Annotation;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use Pitch\Annotation\Fixtures\MyAnnotatedClass;
use Pitch\Annotation\Fixtures\MyPropertiesAnnotation;
use Pitch\Annotation\Fixtures\MyAttributedClass;
use Pitch\Annotation\Fixtures\MyCombinedClass;
use Pitch\Annotation\Fixtures\MySettersAnnotation;
use Pitch\Annotation\Reader as PitchReader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class ReaderTest extends TestCase
{
    public function testReadAnnotations()
    {
        $reader = new PitchReader(new AnnotationReader());

        $reflClass = new ReflectionClass(MyAnnotatedClass::class);
        $reflMethod = new ReflectionMethod(MyAnnotatedClass::class, 'myMethod');
        $reflProperty = new ReflectionProperty(MyAnnotatedClass::class, 'myProperty');

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 1]),
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 2]),
            ],
            $reader->getAnnotations($reflClass)->all(),
        );

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 1]),
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 2]),
                new MySettersAnnotation(['value' => 'Method annotation', 'id' => 3]),
            ],
            $reader->getAnnotations($reflMethod)->all(),
        );

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 1]),
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 2]),
                new MyPropertiesAnnotation(['value' => 'Property annotation', 'id' => 1]),
            ],
            $reader->getAnnotations($reflProperty)->all(),
        );
    }

    /**
     * @requires PHP >= 8
     */
    public function testReadAttributes()
    {
        $reader = new PitchReader();

        $reflClass = new ReflectionClass(MyAttributedClass::class);
        $reflMethod = new ReflectionMethod(MyAttributedClass::class, 'myMethod');
        $reflProperty = new ReflectionProperty(MyAttributedClass::class, 'myProperty');

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class attribute', 'id' => 3]),
            ],
            $reader->getAnnotations($reflClass)->all(),
        );

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class attribute', 'id' => 3]),
                new MyPropertiesAnnotation(['value' => 'Method attribute', 'id' => 1]),
                new MySettersAnnotation(['value' => 'Method attribute', 'id' => 2]),
            ],
            $reader->getAnnotations($reflMethod)->all(),
        );

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class attribute', 'id' => 3]),
                new MyPropertiesAnnotation(['value' => 'Property attribute', 'id' => 2]),
            ],
            $reader->getAnnotations($reflProperty)->all(),
        );
    }

    /**
     * @requires PHP >= 8
     */
    public function testReadCombined()
    {
        $reader = new PitchReader(new AnnotationReader());

        $reflMethod = new ReflectionMethod(MyCombinedClass::class, 'myMethod');
        $reflProperty = new ReflectionProperty(MyCombinedClass::class, 'myProperty');

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 1]),
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 2]),
                new MyPropertiesAnnotation(['value' => 'Class attribute', 'id' => 3]),
                new MySettersAnnotation(['value' => 'Method annotation', 'id' => 3]),
                new MyPropertiesAnnotation(['value' => 'Method attribute', 'id' => 1]),
                new MySettersAnnotation(['value' => 'Method attribute', 'id' => 2]),
            ],
            $reader->getAnnotations($reflMethod)->all(),
        );

        $this->assertEquals(
            [
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 1]),
                new MyPropertiesAnnotation(['value' => 'Class annotation', 'id' => 2]),
                new MyPropertiesAnnotation(['value' => 'Class attribute', 'id' => 3]),
                new MyPropertiesAnnotation(['value' => 'Property annotation', 'id' => 1]),
                new MyPropertiesAnnotation(['value' => 'Property attribute', 'id' => 2]),
            ],
            $reader->getAnnotations($reflProperty)->all(),
        );
    }
}
