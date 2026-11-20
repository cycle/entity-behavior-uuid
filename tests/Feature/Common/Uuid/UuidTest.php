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
use Testo\Assert;
use Testo\Data\DataProvider;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

final class UuidTest extends UuidTestCase
{
    protected Registry $registry;
    protected Tokenizer $tokenizer;

    #[Test]
    #[DataProvider('readersDataProvider')]
    public function testColumnExist(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(User::class)->getFields();

        Assert::true($fields->has('uuid'));
        Assert::true($fields->hasColumn('uuid'));
        Assert::same($fields->get('uuid')->getType(), 'uuid');
        Assert::same($fields->get('uuid')->getTypecast(), [Uuid::class, 'fromString']);
        Assert::same($fields->get('uuid')->getGenerated(), GeneratedField::BEFORE_INSERT);
        Assert::same($fields->count(), 1);
    }

    #[Test]
    #[DataProvider('readersDataProvider')]
    public function testAddColumn(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(Post::class)->getFields();

        Assert::true($fields->has('customUuid'));
        Assert::true($fields->hasColumn('custom_uuid'));
        Assert::same($fields->get('customUuid')->getType(), 'uuid');
        Assert::same($fields->get('customUuid')->getTypecast(), [Uuid::class, 'fromString']);
        Assert::same($fields->get('customUuid')->getGenerated(), GeneratedField::BEFORE_INSERT);
    }

    #[Test]
    #[DataProvider('readersDataProvider')]
    public function testMultipleUuid(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(MultipleUuid::class)->getFields();

        Assert::true($fields->has('uuid'));
        Assert::true($fields->hasColumn('uuid'));
        Assert::same($fields->get('uuid')->getType(), 'uuid');
        Assert::same($fields->get('uuid')->getTypecast(), [Uuid::class, 'fromString']);
        Assert::same($fields->get('uuid')->getGenerated(), GeneratedField::BEFORE_INSERT);

        Assert::true($fields->has('otherUuid'));
        Assert::true($fields->hasColumn('other_uuid'));
        Assert::same($fields->get('otherUuid')->getType(), 'uuid');
        Assert::same($fields->get('otherUuid')->getTypecast(), [Uuid::class, 'fromString']);
        Assert::same($fields->get('otherUuid')->getGenerated(), GeneratedField::BEFORE_INSERT);

        Assert::true($fields->has('uuid7'));
        Assert::true($fields->hasColumn('uuid7'));
        Assert::same($fields->get('uuid7')->getType(), 'uuid');
        Assert::same($fields->get('uuid7')->getTypecast(), [Uuid::class, 'fromString']);
        Assert::same($fields->get('uuid7')->getGenerated(), GeneratedField::BEFORE_INSERT);

        Assert::true($fields->has('otherUuid7'));
        Assert::true($fields->hasColumn('other_uuid7'));
        Assert::same($fields->get('otherUuid7')->getType(), 'uuid');
        Assert::same($fields->get('otherUuid7')->getTypecast(), [Uuid::class, 'fromString']);
        Assert::same($fields->get('otherUuid7')->getGenerated(), GeneratedField::BEFORE_INSERT);
    }

    #[Test]
    #[DataProvider('readersDataProvider')]
    public function testAddNullableColumn(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(NullableUuid::class)->getFields();

        Assert::true($fields->has('notDefinedUuid'));
        Assert::true($fields->hasColumn('not_defined_uuid'));
        Assert::same($fields->get('notDefinedUuid')->getType(), 'uuid');
        Assert::same($fields->get('notDefinedUuid')->getTypecast(), [Uuid::class, 'fromString']);
        Assert::true($this->registry
            ->getTableSchema($this->registry->getEntity(NullableUuid::class))
            ->column('not_defined_uuid')
            ->isNullable());
        Assert::null($fields->get('notDefinedUuid')->getGenerated());
    }

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->tokenizer = new Tokenizer(new TokenizerConfig([
            'directories' => [\dirname(__DIR__, 3) . '/Fixtures/Uuid'],
            'exclude' => [],
        ]));
    }
}
