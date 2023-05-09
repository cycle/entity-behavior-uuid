<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\Uuid;

use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\MultipleUuid;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\Post;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\User;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\BaseTest;
use Cycle\Schema\Registry;
use Ramsey\Uuid\Uuid;
use Spiral\Attributes\ReaderInterface;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;

abstract class UuidTest extends BaseTest
{
    protected Registry $registry;
    protected Tokenizer $tokenizer;

    public function setUp(): void
    {
        parent::setUp();

        $this->tokenizer = new Tokenizer(new TokenizerConfig([
            'directories' => [dirname(__DIR__, 4) . '/Fixtures/Uuid'],
            'exclude' => [],
        ]));
    }


    /**
     * @dataProvider readersDataProvider
     */
    public function testColumnExist(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(User::class)->getFields();

        $this->assertTrue($fields->has('uuid'));
        $this->assertTrue($fields->hasColumn('uuid'));
        $this->assertSame('uuid', $fields->get('uuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('uuid')->getTypecast());
        $this->assertSame(1, $fields->count());
    }

    /**
     * @dataProvider readersDataProvider
     */
    public function testAddColumn(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(Post::class)->getFields();

        $this->assertTrue($fields->has('customUuid'));
        $this->assertTrue($fields->hasColumn('custom_uuid'));
        $this->assertSame('uuid', $fields->get('customUuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('customUuid')->getTypecast());
    }

    /**
     * @dataProvider readersDataProvider
     */
    public function testMultipleUuid(ReaderInterface $reader): void
    {
        $this->compileWithTokenizer($this->tokenizer, $reader);

        $fields = $this->registry->getEntity(MultipleUuid::class)->getFields();

        $this->assertTrue($fields->has('uuid'));
        $this->assertTrue($fields->hasColumn('uuid'));
        $this->assertSame('uuid', $fields->get('uuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('uuid')->getTypecast());

        $this->assertTrue($fields->has('otherUuid'));
        $this->assertTrue($fields->hasColumn('other_uuid'));
        $this->assertSame('uuid', $fields->get('otherUuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('otherUuid')->getTypecast());

        $this->assertTrue($fields->has('uuid7'));
        $this->assertTrue($fields->hasColumn('uuid7'));
        $this->assertSame('uuid', $fields->get('uuid7')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('uuid7')->getTypecast());

        $this->assertTrue($fields->has('otherUuid7'));
        $this->assertTrue($fields->hasColumn('other_uuid7'));
        $this->assertSame('uuid', $fields->get('otherUuid7')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('otherUuid7')->getTypecast());
    }
}
