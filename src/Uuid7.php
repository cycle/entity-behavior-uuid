<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid;

use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid7 as Listener;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Doctrine\Common\Annotations\Annotation\Target;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Uses a version 7 (Unix Epoch Time) UUID
 *
 * @Annotation
 * @NamedArgumentConstructor()
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE), NamedArgumentConstructor]
final class Uuid7 extends Uuid
{
    /**
     * @param non-empty-string $field Uuid property name
     * @param non-empty-string|null $column Uuid column name
     * @param bool $generate Indicates whether to generate a new UUID or not
     *
     * @see \Ramsey\Uuid\UuidFactoryInterface::uuid7()
     */
    public function __construct(
        string $field = 'uuid',
        ?string $column = null,
        private bool $generate = true
    ) {
        $this->field = $field;
        $this->column = $column;
    }

    protected function getListenerClass(): string
    {
        return Listener::class;
    }

    #[ArrayShape(['field' => 'string', 'generate' => 'bool'])]
    protected function getListenerArgs(): array
    {
        return [
            'field' => $this->field,
            'generate' => $this->generate
        ];
    }
}
