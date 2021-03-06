<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\Uuid;

use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\Post;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid\User;
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\BaseTest;
use Cycle\Schema\Registry;
use Ramsey\Uuid\Uuid;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;

abstract class UuidTest extends BaseTest
{
    protected Registry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->compileWithTokenizer(new Tokenizer(new TokenizerConfig([
            'directories' => [dirname(__DIR__, 4) . '/Fixtures/Uuid'],
            'exclude' => [],
        ])));
    }

    public function testColumnExist(): void
    {
        $fields = $this->registry->getEntity(User::class)->getFields();

        $this->assertTrue($fields->has('uuid'));
        $this->assertTrue($fields->hasColumn('uuid'));
        $this->assertSame('uuid', $fields->get('uuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('uuid')->getTypecast());
        $this->assertSame(1, $fields->count());
    }

    public function testAddColumn(): void
    {
        $fields = $this->registry->getEntity(Post::class)->getFields();

        $this->assertTrue($fields->has('customUuid'));
        $this->assertTrue($fields->hasColumn('custom_uuid'));
        $this->assertSame('uuid', $fields->get('customUuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('customUuid')->getTypecast());
    }
}
