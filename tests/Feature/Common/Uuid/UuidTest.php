<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Feature\Common\Uuid;

use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\MultipleUuid;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\NullableUuid;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\Post;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\User;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Support\UuidTestCase;
use Cycle\ORM\Schema\GeneratedField;
use Cycle\Schema\Registry;
use Ramsey\Uuid\Uuid;
use Spiral\Attributes\ReaderInterface;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;

final class UuidTest extends UuidTestCase
{
    protected Registry $registry;
    protected Tokenizer $tokenizer;

    #[\Testo\Test]
    #[\Testo\Data\DataProvider('readersDataProvider')]
    public function testColumnExist(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(User::class)->getFields();

        \Testo\Assert::true($fields->has('uuid'));
        \Testo\Assert::true($fields->hasColumn('uuid'));
        \Testo\Assert::same($fields->get('uuid')->getType(), 'uuid');
        \Testo\Assert::same($fields->get('uuid')->getTypecast(), [Uuid::class, 'fromString']);
        \Testo\Assert::same($fields->get('uuid')->getGenerated(), GeneratedField::BEFORE_INSERT);
        \Testo\Assert::same($fields->count(), 1);
    }

    #[\Testo\Test]
    #[\Testo\Data\DataProvider('readersDataProvider')]
    public function testAddColumn(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(Post::class)->getFields();

        \Testo\Assert::true($fields->has('customUuid'));
        \Testo\Assert::true($fields->hasColumn('custom_uuid'));
        \Testo\Assert::same($fields->get('customUuid')->getType(), 'uuid');
        \Testo\Assert::same($fields->get('customUuid')->getTypecast(), [Uuid::class, 'fromString']);
        \Testo\Assert::same($fields->get('customUuid')->getGenerated(), GeneratedField::BEFORE_INSERT);
    }

    #[\Testo\Test]
    #[\Testo\Data\DataProvider('readersDataProvider')]
    public function testMultipleUuid(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(MultipleUuid::class)->getFields();

        \Testo\Assert::true($fields->has('uuid'));
        \Testo\Assert::true($fields->hasColumn('uuid'));
        \Testo\Assert::same($fields->get('uuid')->getType(), 'uuid');
        \Testo\Assert::same($fields->get('uuid')->getTypecast(), [Uuid::class, 'fromString']);
        \Testo\Assert::same($fields->get('uuid')->getGenerated(), GeneratedField::BEFORE_INSERT);

        \Testo\Assert::true($fields->has('otherUuid'));
        \Testo\Assert::true($fields->hasColumn('other_uuid'));
        \Testo\Assert::same($fields->get('otherUuid')->getType(), 'uuid');
        \Testo\Assert::same($fields->get('otherUuid')->getTypecast(), [Uuid::class, 'fromString']);
        \Testo\Assert::same($fields->get('otherUuid')->getGenerated(), GeneratedField::BEFORE_INSERT);

        \Testo\Assert::true($fields->has('uuid7'));
        \Testo\Assert::true($fields->hasColumn('uuid7'));
        \Testo\Assert::same($fields->get('uuid7')->getType(), 'uuid');
        \Testo\Assert::same($fields->get('uuid7')->getTypecast(), [Uuid::class, 'fromString']);
        \Testo\Assert::same($fields->get('uuid7')->getGenerated(), GeneratedField::BEFORE_INSERT);

        \Testo\Assert::true($fields->has('otherUuid7'));
        \Testo\Assert::true($fields->hasColumn('other_uuid7'));
        \Testo\Assert::same($fields->get('otherUuid7')->getType(), 'uuid');
        \Testo\Assert::same($fields->get('otherUuid7')->getTypecast(), [Uuid::class, 'fromString']);
        \Testo\Assert::same($fields->get('otherUuid7')->getGenerated(), GeneratedField::BEFORE_INSERT);
    }

    #[\Testo\Test]
    #[\Testo\Data\DataProvider('readersDataProvider')]
    public function testAddNullableColumn(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(NullableUuid::class)->getFields();

        \Testo\Assert::true($fields->has('notDefinedUuid'));
        \Testo\Assert::true($fields->hasColumn('not_defined_uuid'));
        \Testo\Assert::same($fields->get('notDefinedUuid')->getType(), 'uuid');
        \Testo\Assert::same($fields->get('notDefinedUuid')->getTypecast(), [Uuid::class, 'fromString']);
        \Testo\Assert::true($this->registry
            ->getTableSchema($this->registry->getEntity(NullableUuid::class))
            ->column('not_defined_uuid')
            ->isNullable());
        \Testo\Assert::null($fields->get('notDefinedUuid')->getGenerated());
    }

    #[\Testo\Lifecycle\BeforeTest]
    protected function setUp(): void
    {
        $this->tokenizer = new Tokenizer(new TokenizerConfig([
            'directories' => [\dirname(__DIR__, 3) . '/Fixtures/Uuid'],
            'exclude' => [],
        ]));
    }
}
