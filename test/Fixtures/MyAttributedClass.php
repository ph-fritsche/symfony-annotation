<?php
namespace Pitch\Annotation\Fixtures;

use Pitch\Annotation\Fixtures\MyPropertiesAnnotation;
use Pitch\Annotation\Fixtures\MySettersAnnotation;

#[MyPropertiesAnnotation(id: 3, value: 'Class attribute')]
class MyAttributedClass
{
    #[MyPropertiesAnnotation('Method attribute', id: 1)]
    #[MySettersAnnotation('Method attribute', id: 2)]
    public function myMethod()
    {
    }

    #[MyPropertiesAnnotation(['value' => 'Property attribute', 'id' => 2])]
    public $myProperty;
}
