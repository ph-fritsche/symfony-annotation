<?php
namespace Pitch\Annotation;

use Doctrine\Common\Annotations\Reader as DoctrineReader;
use Reflector;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class Reader
{
    protected ?DoctrineReader $doctrineReader;
    protected string $annotationClass;

    public function __construct(
        ?DoctrineReader $doctrineReader = null,
        string $annotationClass = Annotation::class
    ) {
        $this->doctrineReader = $doctrineReader;
        $this->annotationClass = $annotationClass;
    }

    /**
     * @param string[] $interfaces
     */
    public function getAnnotations(
        Reflector $reflector
    ) {
        $parentAnnotations = ($reflector instanceof ReflectionMethod || $reflector instanceof ReflectionProperty)
            ? $this->getAnnotations($reflector->getDeclaringClass())->all()
            : [];

        $annotations = \array_filter(
            $this->getDoctrineAnnotations($reflector),
            [$this, 'filterAnnotationClass'],
        );

        $attributes = \array_map(
            [$this, 'createAttributeObject'],
            \array_filter(
                $this->getPhpAttributes($reflector),
            )
        );

        return new AnnotationsCollection(...$parentAnnotations, ...$annotations, ...$attributes);
    }

    /**
     * @param string|object $class
     */
    private function filterAnnotationClass(
        $annotation
    ): bool {
        return \is_a($annotation, $this->annotationClass, true);
    }

    private function getDoctrineAnnotations(
        Reflector $reflector
    ) {
        if ($this->doctrineReader) {
            if ($reflector instanceof ReflectionClass) {
                return $this->doctrineReader->getClassAnnotations($reflector);
            } elseif ($reflector instanceof ReflectionMethod) {
                return $this->doctrineReader->getMethodAnnotations($reflector);
            } elseif ($reflector instanceof ReflectionProperty) {
                return $this->doctrineReader->getPropertyAnnotations($reflector);
            }
        }
        return [];
    }

    /**
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflector
     */
    private function getPhpAttributes(
        $reflector
    ) {
        return \PHP_MAJOR_VERSION >= 8
            ? $reflector->getAttributes($this->annotationClass, ReflectionAttribute::IS_INSTANCEOF)
            : [];
    }

    private function createAttributeObject(
        ReflectionAttribute $attr
    ) {
        return $attr->newInstance();
    }
}
