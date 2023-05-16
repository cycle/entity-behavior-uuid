<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\Uuid;

use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid7;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\User;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\BaseTest;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Traits\TableTrait;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid1;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid2;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid3;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid4;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid5;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid6;
use Cycle\ORM\Heap\Heap;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Select;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class ListenerTest extends BaseTest
{
    use TableTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->makeTable(
            'users',
            [
                'uuid' => 'string',
                'optional_uuid' => 'string,nullable'
            ]
        );
    }

    public function testAssignManually(): void
    {
        $this->withListeners(Uuid4::class);

        $user = new User();
        $user->uuid = Uuid::uuid4();
        $bytes = $user->uuid->getBytes();

        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertSame($bytes, $data->uuid->getBytes());
    }

    /**
     * @dataProvider generateFalseDataProvider
     */
    public function testWithGenerateFalse(array $listener): void
    {
        $this->withListeners($listener);

        $user = new User();
        $user->uuid = Uuid::uuid4();

        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchData();

        $this->assertNull($data[0]['optional_uuid']);
    }

    public function testUuid1(): void
    {
        $this->withListeners([Uuid1::class, ['node' => '00000fffffff', 'clockSeq' => 0xffff]]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(UuidInterface::class, $data->uuid);
        $this->assertSame(1, $data->uuid->getVersion());
        $this->assertIsString($data->uuid->toString());
    }

    public function testUuid2(): void
    {
        $this->withListeners([
            Uuid2::class,
            [
                'localDomain' => Uuid::DCE_DOMAIN_PERSON,
                'localIdentifier' => new Integer('12345678')
            ]
        ]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(UuidInterface::class, $data->uuid);
        $this->assertSame(2, $data->uuid->getVersion());
        $this->assertIsString($data->uuid->toString());
    }

    public function testUuid3(): void
    {
        $this->withListeners([
            Uuid3::class,
            [
                'namespace' => Uuid::NAMESPACE_URL,
                'name' => 'https://example.com/foo'
            ]
        ]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(UuidInterface::class, $data->uuid);
        $this->assertSame(3, $data->uuid->getVersion());
        $this->assertIsString($data->uuid->toString());
    }

    public function testUuid4(): void
    {
        $this->withListeners(Uuid4::class);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(UuidInterface::class, $data->uuid);
        $this->assertSame(4, $data->uuid->getVersion());
        $this->assertIsString($data->uuid->toString());
    }

    public function testUuid5(): void
    {
        $this->withListeners([
            Uuid5::class,
            ['namespace' => Uuid::NAMESPACE_URL, 'name' => 'https://example.com/foo']
        ]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(UuidInterface::class, $data->uuid);
        $this->assertSame(5, $data->uuid->getVersion());
        $this->assertIsString($data->uuid->toString());
    }

    public function testUuid6(): void
    {
        $this->withListeners([Uuid6::class, ['node' => new Hexadecimal('0800200c9a66'), 'clockSeq' => 0x1669]]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(UuidInterface::class, $data->uuid);
        $this->assertSame(6, $data->uuid->getVersion());
        $this->assertIsString($data->uuid->toString());
    }

    public function testUuid7(): void
    {
        $this->withListeners(Uuid7::class);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(UuidInterface::class, $data->uuid);
        $this->assertSame(7, $data->uuid->getVersion());
        $this->assertIsString($data->uuid->toString());
    }

    public function withListeners(array|string $listeners): void
    {
        $this->withSchema(new Schema([
            User::class => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'users',
                SchemaInterface::PRIMARY_KEY => 'uuid',
                SchemaInterface::COLUMNS => ['uuid', 'optional_uuid'],
                SchemaInterface::LISTENERS => [$listeners],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
                SchemaInterface::TYPECAST => [
                    'uuid' => [Uuid::class, 'fromString'],
                    'optional_uuid' => [Uuid::class, 'fromString']
                ]
            ]
        ]));
    }

    public static function generateFalseDataProvider(): \Traversable
    {
        yield [
            [
                Uuid1::class,
                [
                    'generate' => false,
                    'field' => 'optional_uuid',
                    'node' => '00000fffffff',
                    'clockSeq' => 0xffff
                ]
            ]
        ];
        yield [
            [
                Uuid2::class,
                [
                    'generate' => false,
                    'field' => 'optional_uuid',
                    'localDomain' => Uuid::DCE_DOMAIN_PERSON,
                    'localIdentifier' => new Integer('12345678')
                ]
            ]
        ];
        yield [
            [
                Uuid3::class,
                [
                    'generate' => false,
                    'field' => 'optional_uuid',
                    'namespace' => Uuid::NAMESPACE_URL,
                    'name' => 'https://example.com/foo'
                ]
            ]
        ];
        yield [[Uuid4::class, ['generate' => false, 'field' => 'optional_uuid']]];
        yield [
            [
                Uuid5::class,
                [
                    'generate' => false,
                    'field' => 'optional_uuid',
                    'namespace' => Uuid::NAMESPACE_URL,
                    'name' => 'https://example.com/foo'
                ]
            ]
        ];
        yield [
            [
                Uuid6::class,
                [
                    'generate' => false,
                    'field' => 'optional_uuid',
                    'node' => new Hexadecimal('0800200c9a66'), 'clockSeq' => 0x1669
                ]
            ]
        ];
        yield [[Uuid7::class, ['generate' => false, 'field' => 'optional_uuid']]];
    }
}
