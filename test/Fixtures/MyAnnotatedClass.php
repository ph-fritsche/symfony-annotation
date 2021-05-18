<?php
namespace Pitch\Annotation\Fixtures;

use Pitch\Annotation\Fixtures\MyPropertiesAnnotation;
use Pitch\Annotation\Fixtures\MySettersAnnotation;

/**
 * @MyPropertiesAnnotation("Class annotation", id=1)
 * @MyPropertiesAnnotation("Class annotation", id=2)
 */
class MyAnnotatedClass
{
    /**
     * @MySettersAnnotation("Method annotation", id=3)
     */
    public function myMethod()
    {
    }

    /**
     * @MyPropertiesAnnotation("Property annotation", id=1)
     */
    public $myProperty;
}
