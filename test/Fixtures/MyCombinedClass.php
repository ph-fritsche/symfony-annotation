<?php
namespace Pitch\Annotation\Fixtures;

use Pitch\Annotation\Fixtures\MyPropertiesAnnotation;
use Pitch\Annotation\Fixtures\MySettersAnnotation;

/**
 * @MyPropertiesAnnotation("Class annotation", id=1)
 * @MyPropertiesAnnotation("Class annotation", id=2)
 */
#[MyPropertiesAnnotation(id: 3, value: 'Class attribute')]
class MyCombinedClass
{

    #[MyPropertiesAnnotation('Method attribute', id: 1)]
    #[MySettersAnnotation('Method attribute', id: 2)]
    /**
     * @MySettersAnnotation("Method annotation", id=3)
     */
    public function myMethod()
    {
    }

    /**
     * @MyPropertiesAnnotation("Property annotation", id=1)
     */
    #[MyPropertiesAnnotation(['value' => 'Property attribute', 'id' => 2])]
    public $myProperty;
}
