<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Unit;

use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnCreate;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid1;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid2;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid3;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid4;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid5;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid6;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid7;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\User;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\SchemaInterface;
use Cycle\Testing\ORM\Memory;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Testo\Assert;
use Testo\Data\DataProvider;
use Testo\Test;

#[Test]
final class ListenerTest
{
    public static function listeners(): iterable
    {
        yield 'uuid1' => [Uuid1::class, ['node' => '00000fffffff', 'clockSeq' => 0xffff], 1];
        yield 'uuid2' => [Uuid2::class, ['localDomain' => Uuid::DCE_DOMAIN_PERSON, 'localIdentifier' => '1001'], 2];
        yield 'uuid3' => [Uuid3::class, ['namespace' => Uuid::NAMESPACE_URL, 'name' => 'https://example.com/foo'], 3];
        yield 'uuid4' => [Uuid4::class, [], 4];
        yield 'uuid5' => [Uuid5::class, ['namespace' => Uuid::NAMESPACE_URL, 'name' => 'https://example.com/foo'], 5];
        yield 'uuid6' => [Uuid6::class, ['node' => '0800200c9a66'], 6];
        yield 'uuid7' => [Uuid7::class, [], 7];
    }

    #[DataProvider('listeners')]
    public function registersUuidOfItsVersion(string $class, array $args, int $version): void
    {
        $event = self::onCreate(['token' => null]);

        (new $class(...$args, field: 'token'))($event);

        $uuid = $event->state->getData()['token'];
        Assert::instanceOf($uuid, UuidInterface::class);
        Assert::same($uuid->getVersion(), $version);
    }

    #[DataProvider('listeners')]
    public function keepsAssignedValue(string $class, array $args): void
    {
        $assigned = Uuid::uuid4();
        $event = self::onCreate(['token' => $assigned]);

        (new $class(...$args, field: 'token'))($event);

        Assert::same($event->state->getData()['token'], $assigned);
    }

    #[DataProvider('listeners')]
    public function skipsNullableField(string $class, array $args): void
    {
        $event = self::onCreate([]);

        (new $class(...$args, field: 'token', nullable: true))($event);

        Assert::same($event->state->getData(), []);
    }

    public function nameBasedUuidsAreDerivedFromNamespaceAndName(): void
    {
        $event = self::onCreate([]);

        (new Uuid3(Uuid::NAMESPACE_URL, 'https://example.com/foo', 'md5'))($event);
        (new Uuid5(Uuid::NAMESPACE_URL, 'https://example.com/foo', 'sha1'))($event);

        Assert::same($event->state->getData()['md5']->toString(), Uuid::uuid3(Uuid::NAMESPACE_URL, 'https://example.com/foo')->toString());
        Assert::same($event->state->getData()['sha1']->toString(), Uuid::uuid5(Uuid::NAMESPACE_URL, 'https://example.com/foo')->toString());
    }

    private static function onCreate(array $data): OnCreate
    {
        $orm = Memory::orm([
            'user' => [
                SchemaInterface::ENTITY => User::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'users',
                SchemaInterface::PRIMARY_KEY => 'uuid',
                SchemaInterface::COLUMNS => ['uuid', 'token'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
        ]);

        return new OnCreate(
            'user',
            $orm->getMapper('user'),
            new User(),
            new Node(Node::NEW, $data, 'user'),
            new State(Node::NEW, $data),
            $orm->getSource('user'),
            new \DateTimeImmutable(),
        );
    }
}
