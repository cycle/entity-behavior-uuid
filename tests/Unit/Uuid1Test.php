<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Unit;

use Cycle\ORM\Entity\Behavior\Dispatcher\ListenerProvider;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid1 as Listener;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid1;
use Cycle\ORM\SchemaInterface;
use Ramsey\Uuid\Type\Hexadecimal;
use Testo\Assert;
use Testo\Data\DataProvider;
use Testo\Test;

#[Test]
final class Uuid1Test
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
                            'node' => null,
                            'clockSeq' => null,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            [],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'node' => null,
                            'clockSeq' => null,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            ['custom_uuid'],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'node' => 'foo',
                            'clockSeq' => null,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            ['custom_uuid', null, 'foo'],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'node' => 'foo',
                            'clockSeq' => 3,
                            'nullable' => false,
                        ],
                    ],
                ],
            ],
            ['custom_uuid', null, 'foo', 3],
        ];
        yield [
            [
                SchemaInterface::LISTENERS => [
                    [
                        ListenerProvider::DEFINITION_CLASS => Listener::class,
                        ListenerProvider::DEFINITION_ARGS => [
                            'field' => 'custom_uuid',
                            'node' => 'foo',
                            'clockSeq' => 3,
                            'nullable' => true,
                        ],
                    ],
                ],
            ],
            ['custom_uuid', null, 'foo', 3, true],
        ];
    }

    #[DataProvider('schemaDataProvider')]
    public function testModifySchema(array $expected, array $args): void
    {
        $schema = [];
        $uuid = new Uuid1(...$args);
        $uuid->modifySchema($schema);

        Assert::same($schema, $expected);
    }

    public function testHexadecimalNodeIsPassedAsString(): void
    {
        $schema = [];

        (new Uuid1(node: new Hexadecimal('0800200c9a66')))->modifySchema($schema);

        Assert::same($schema[SchemaInterface::LISTENERS][0][ListenerProvider::DEFINITION_ARGS]['node'], '0800200c9a66');
    }
}
