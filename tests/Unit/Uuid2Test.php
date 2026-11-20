<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Unit;

use Cycle\ORM\Entity\Behavior\Dispatcher\ListenerProvider;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid2 as Listener;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid2;
use Cycle\ORM\SchemaInterface;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Testo\Assert;
use Testo\Data\DataProvider;
use Testo\Test;

#[Test]
final class Uuid2Test
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
                            'localDomain' => 3,
                            'localIdentifier' => null,
                            'node' => null,
                            'clockSeq' => null,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            [3],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'localDomain' => 3,
                            'localIdentifier' => null,
                            'node' => null,
                            'clockSeq' => null,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            [3, 'custom_uuid'],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'localDomain' => 3,
                            'localIdentifier' => 'foo',
                            'node' => null,
                            'clockSeq' => null,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            [3, 'custom_uuid', null, 'foo'],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'localDomain' => 3,
                            'localIdentifier' => 'foo',
                            'node' => 'bar',
                            'clockSeq' => null,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            [3, 'custom_uuid', null, 'foo', 'bar'],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'localDomain' => 3,
                            'localIdentifier' => 'foo',
                            'node' => 'bar',
                            'clockSeq' => 4,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            [3, 'custom_uuid', null, 'foo', 'bar', 4],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'localDomain' => 3,
                            'localIdentifier' => 'foo',
                            'node' => 'bar',
                            'clockSeq' => 4,
                            'nullable' => true,
                        ],
                    ],
                ],
            ],
            [3, 'custom_uuid', null, 'foo', 'bar', 4, true],
        ];
    }

    #[DataProvider('schemaDataProvider')]
    public function testModifySchema(array $expected, array $args): void
    {
        $schema = [];
        $uuid = new Uuid2(...$args);
        $uuid->modifySchema($schema);

        Assert::same($schema, $expected);
    }

    public function testObjectArgumentsArePassedAsStrings(): void
    {
        $schema = [];

        (new Uuid2(
            localDomain: 0,
            localIdentifier: new IntegerObject('1001'),
            node: new Hexadecimal('0800200c9a66'),
        ))->modifySchema($schema);

        $args = $schema[SchemaInterface::LISTENERS][0][ListenerProvider::DEFINITION_ARGS];
        Assert::same($args['localIdentifier'], '1001');
        Assert::same($args['node'], '0800200c9a66');
    }
}
