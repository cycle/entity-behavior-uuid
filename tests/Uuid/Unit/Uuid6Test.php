<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Unit;

use Cycle\ORM\Entity\Behavior\Dispatcher\ListenerProvider;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid6;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid6 as Listener;
use Cycle\ORM\SchemaInterface;
use PHPUnit\Framework\TestCase;

final class Uuid6Test extends TestCase
{
    /**
     * @dataProvider schemaDataProvider
     */
    public function testModifySchema(array $expected, array $args): void
    {
        $schema = [];
        $uuid = new Uuid6(...$args);
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
                            'node' => null,
                            'clockSeq' => null,
                            'generate' => true
                        ],
                    ]
                ]
            ],
            []
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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            ['custom_uuid']
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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            ['custom_uuid', null, 'foo']
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
                            'generate' => true
                        ],
                    ]
                ]
            ],
            ['custom_uuid', null, 'foo', 3]
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
                            'generate' => false
                        ],
                    ]
                ]
            ],
            ['custom_uuid', null, 'foo', 3, false]
        ];
    }
}
