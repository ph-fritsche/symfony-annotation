<?php
namespace Pitch\Annotation;

use PHPUnit\Framework\TestCase;
use Pitch\Annotation\Fixtures\MyPropertiesAnnotation;
use Pitch\Annotation\Fixtures\MySettersAnnotation;
use stdClass;

class AnnotationsCollectionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->collection = new AnnotationsCollection(
            new MyPropertiesAnnotation("propFoo"),
            new MyPropertiesAnnotation("propBar"),
            new MySettersAnnotation("setFoo"),
            new MyPropertiesAnnotation("propBaz"),
            new MySettersAnnotation("setBar"),
        );
    }

    public function testAll()
    {
        $this->assertEquals(
            [
                new MyPropertiesAnnotation("propFoo"),
                new MyPropertiesAnnotation("propBar"),
                new MySettersAnnotation("setFoo"),
                new MyPropertiesAnnotation("propBaz"),
                new MySettersAnnotation("setBar"),
            ],
            $this->collection->all(),
        );

        $this->assertEquals(
            [
                new MyPropertiesAnnotation("propFoo"),
                new MyPropertiesAnnotation("propBar"),
                3 => new MyPropertiesAnnotation("propBaz"),
            ],
            $this->collection->all(MyPropertiesAnnotation::class),
        );
    }

    public function testFirst()
    {
        $this->assertEquals(
            new MyPropertiesAnnotation("propFoo"),
            $this->collection->first(),
        );

        $this->assertEquals(
            new MySettersAnnotation("setFoo"),
            $this->collection->first(MySettersAnnotation::class),
        );

        $this->assertEquals(
            null,
            $this->collection->first(stdClass::class),
        );
    }

    public function testLast()
    {
        $this->assertEquals(
            new MySettersAnnotation("setBar"),
            $this->collection->last(),
        );

        $this->assertEquals(
            new MyPropertiesAnnotation("propBaz"),
            $this->collection->last(MyPropertiesAnnotation::class),
        );

        $this->assertEquals(
            null,
            $this->collection->last(stdClass::class),
        );
    }
}
