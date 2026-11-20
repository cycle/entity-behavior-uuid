<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Unit;

use Cycle\ORM\Entity\Behavior\Dispatcher\ListenerProvider;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid3 as Listener;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid3;
use Cycle\ORM\SchemaInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Testo\Assert;
use Testo\Data\DataProvider;
use Testo\Test;

#[Test]
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

    #[DataProvider('schemaDataProvider')]
    public function testModifySchema(array $expected, array $args): void
    {
        $schema = [];
        $uuid = new Uuid3(...$args);
        $uuid->modifySchema($schema);

        Assert::same($schema, $expected);
    }

    public function testUuidNamespaceIsPassedAsString(): void
    {
        $schema = [];

        (new Uuid3(RamseyUuid::fromString(RamseyUuid::NAMESPACE_URL), 'bar'))->modifySchema($schema);

        Assert::same($schema[SchemaInterface::LISTENERS][0][ListenerProvider::DEFINITION_ARGS]['namespace'], RamseyUuid::NAMESPACE_URL);
    }
}
