<?php
namespace Pitch\Annotation;

class AnnotationsCollection
{
    private array $annotations;

    public function __construct(
        object ...$annotations
    ) {
        $this->annotations = $annotations;
    }

    /**
     * @return object[]
     */
    public function all(
        ?string $interfaceOrClass = null
    ): array {
        return isset($interfaceOrClass)
            ? \array_filter(
                $this->annotations,
                fn($a) => \is_a($a, $interfaceOrClass),
            )
            : $this->annotations;
    }

    public function first(
        ?string $interfaceOrClass = null
    ): ?object {
        \reset($this->annotations);

        do {
            if (!isset($interfaceOrClass) || \is_a(\current($this->annotations), $interfaceOrClass)) {
                return \current($this->annotations);
            }
        } while (\next($this->annotations));

        return null;
    }

    public function last(
        ?string $interfaceOrClass = null
    ): ?object {
        \end($this->annotations);

        do {
            if (!isset($interfaceOrClass) || \is_a(\current($this->annotations), $interfaceOrClass)) {
                return \current($this->annotations);
            }
        } while (\prev($this->annotations));

        return null;
    }
}
