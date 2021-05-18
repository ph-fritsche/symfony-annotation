<?php
namespace Pitch\Annotation;

use Pitch\Annotation\DependencyInjection\PitchAnnotationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/*
 * The class needs to be named exactly as the first part of the namespace.
 */
class PitchAnnotationBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        return PitchAnnotationExtension::class;
    }
}
