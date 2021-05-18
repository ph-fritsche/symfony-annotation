<?php
namespace Pitch\Annotation\Fixtures;

use Attribute;
use Pitch\Annotation\AbstractAnnotation;

/**
 * @Annotation
 */
#[Attribute]
class MyPropertiesAnnotation extends AbstractAnnotation
{
    public string $value;
    public int $id;
}
