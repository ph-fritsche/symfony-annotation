<?php
namespace Pitch\Annotation\Fixtures;

use Attribute;
use Pitch\Annotation\AbstractAnnotation;

/**
 * @Annotation
 */
#[Attribute]
class MySettersAnnotation extends AbstractAnnotation
{
    public string $theValue;
    public int $theId;

    protected function setValue(string $v) {
        $this->theValue = "<<< " . $v . " >>>";
    }

    protected function setId(int $v) {
        $this->theId = $v * 100;
    }
}
