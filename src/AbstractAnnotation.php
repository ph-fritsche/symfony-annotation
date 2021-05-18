<?php

namespace Pitch\Annotation;

use LogicException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;

abstract class AbstractAnnotation implements Annotation
{
    public function __construct(
        ...$args
    ) {
        $values = \count($args) === 1 && isset($args[0]) && is_array($args[0]) ? $args[0] : $args;

        foreach ($values as $propertyName => $v) {
            if ($propertyName === 0) {
                $propertyName = 'value';
            }

            $setterName = 'set' . \ucwords($propertyName);
            if (\method_exists($this, $setterName)) {
                $reflMethod = new ReflectionMethod($this, $setterName);

                $this->typecast(
                    $v,
                    $reflMethod->getNumberOfParameters() >= 1
                        ? $reflMethod->getParameters()[0]->getType()
                        : null,
                );

                $this->$setterName($v);
            } elseif (\property_exists($this, $propertyName)) {
                $reflProp = new ReflectionProperty($this, $propertyName);

                $this->typecast($v, $reflProp->getType());

                $this->$propertyName = $v;
            } else {
                throw new LogicException(\sprintf(
                    'Unknown key "%s" for annotation "@%s".',
                    $propertyName,
                    \get_class($this)
                ));
            }
        }
    }

    private function typecast(
        &$value,
        ?ReflectionType $type
    ) {
        if (
            $type instanceof ReflectionNamedType
            && gettype($value) !== $type->getName()
            && !is_a($value, $type->getName())
        ) {
            settype($value, $type->getName());
        }
    }
}
