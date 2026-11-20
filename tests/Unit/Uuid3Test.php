<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Unit;

use Cycle\ORM\Entity\Behavior\Dispatcher\ListenerProvider;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid3 as Listener;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid3;
use Cycle\ORM\SchemaInterface;
use PHPUnit\Framework\TestCase;

final class Uuid3Test
{
    public static function schemaDataProvider(): \Traversable
    {
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'uuid',
                            'namespace' => 'foo',
                            'name' => 'bar',
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            ['foo', 'bar'],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'namespace' => 'foo',
                            'name' => 'bar',
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            ['foo', 'bar', 'custom_uuid'],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'namespace' => 'foo',
                            'name' => 'bar',
                            'nullable' => true,
                        ],
                    ],
                ],
            ],
            ['foo', 'bar', 'custom_uuid', null, true],
        ];
    }

    #[\Testo\Test]
    #[\Testo\Data\DataProvider('schemaDataProvider')]
    public function testModifySchema(array $expected, array $args): void
    {
        $schema = [];
        $uuid = new Uuid3(...$args);
        $uuid->modifySchema($schema);

        \Testo\Assert::same($schema, $expected);
    }
}
