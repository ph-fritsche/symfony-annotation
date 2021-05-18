# Annotation

This bundle provides an unified API for [Doctrine Annotations](https://www.doctrine-project.org/projects/annotations.html) and [PHP8 Attributes](https://www.php.net/manual/de/language.attributes.overview.php) as (Controller) Annotations.

## Usage

### Read attributes and annotations

```php
namespace App;

use Attribute;
use Doctrine\Common\Annotation\Reader as DoctrineReader;
use Pitch\Annotation\Annotation;
use Pitch\Annotation\Reader as PitchReader;

#[Attribute]
class MyAnnotation implements Annotation
{
    public string $value;
}

/**
 * @MyAnnotation('foo')
 */
class MyClass
{
    #[MyAnnotation('bar')]
    public function myMethod() {}
}

$pitchReader = new PitchReader(new DoctrineReader());
$reflection = new ReflectionMethod(MyClass::class, 'myMethod');

foreach($pitchReader->getAnnotations($reflection)->all() as $annotation) {
    echo $annotation->value; // outputs: foobar
}
```

### Controller request attributes

This bundle registers an `EventSubscriber` on the `kernel.controller` event
and stores the controller annotations on `Request::attributes`,
so that they can easily be accessed on other events.

```php
namespace App\Annotation;

#[Attribute]
class MyAnnotation
{
    public function __construct(
        public string $value,
    ) {}
}
```

```php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Annotation\MyAnnotation;

class MyController {
    #[MyAnnotation("foo")]
    #[MyAnnotation("bar")]
    public function __invoke(Request $request)
    {
        foreach ($request->attributes->get('_' . MyAnnotation::class) as $a) {
            echo $a->value; // outputs: foobar
        }
    }
}
```
