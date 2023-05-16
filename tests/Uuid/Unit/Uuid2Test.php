<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Unit;

use Cycle\ORM\Entity\Behavior\Dispatcher\ListenerProvider;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid2;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid2 as Listener;
use Cycle\ORM\SchemaInterface;
use PHPUnit\Framework\TestCase;

final class Uuid2Test extends TestCase
{
    /**
     * @dataProvider schemaDataProvider
     */
    public function testModifySchema(array $expected, array $args): void
    {
        $schema = [];
        $uuid = new Uuid2(...$args);
        $uuid->modifySchema($schema);

        $this->assertSame($expected, $schema);
    }

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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            [3]
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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            [3, 'custom_uuid']
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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            [3, 'custom_uuid', null, 'foo']
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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            [3, 'custom_uuid', null, 'foo', 'bar']
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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            [3, 'custom_uuid', null, 'foo', 'bar', 4],
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
                            'generate' => false
                        ],
                    ]
                ]
            ],
            [3, 'custom_uuid', null, 'foo', 'bar', 4, false]
        ];
    }
}
