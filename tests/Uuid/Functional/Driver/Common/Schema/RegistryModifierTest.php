<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Macros\Uuid\Tests\Functional\Driver\Common\Schema;

use Cycle\ORM\Entity\Macros\Exception\MacroCompilationException;
use Cycle\ORM\Entity\Macros\Uuid\Common\Schema\RegistryModifier;
use Cycle\ORM\Entity\Macros\Uuid\Tests\Functional\Driver\Common\BaseTest;
use Cycle\ORM\Entity\Macros\Uuid\Uuid\UuidTypecast;
use Cycle\Schema\Definition\Entity;
use Cycle\Schema\Registry;

abstract class RegistryModifierTest extends BaseTest
{
    private const ROLE_TEST = 'test';

    protected Registry $registry;
    protected RegistryModifier $modifier;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry($this->dbal);

        $entity = (new Entity())->setRole(self::ROLE_TEST);
        $this->registry->register($entity);
        $this->registry->linkTable($entity, 'default', 'tests');

        $this->modifier = new RegistryModifier($this->registry, self::ROLE_TEST);
    }

    public function testAddUuidField(): void
    {
        $this->modifier->addUuidColumn('uuid_column', 'uuid');

        $entity = $this->registry->getEntity(self::ROLE_TEST);
        $fields = $entity->getFields();

        $this->assertTrue($fields->has('uuid'));
        $this->assertSame('uuid', $fields->get('uuid')->getType());
        $this->assertSame('uuid_column', $fields->get('uuid')->getColumn());
        $this->assertSame([UuidTypecast::class, 'cast'], $fields->get('uuid')->getTypecast());
    }

    public function testAddWithExistenceField(): void
    {
        $this->modifier->addStringColumn('uuid_column', 'uuid');

        $this->expectExceptionMessage('Field uuid must be of type uuid.');
        $this->expectException(MacroCompilationException::class);

        $this->modifier->addUuidColumn('uuid_column', 'uuid');
    }

    public function testAddWithWrongColumnName(): void
    {
        $this->modifier->addUuidColumn('uuid_column', 'uuid');

        $this->expectExceptionMessageMatches('/Ambiguous column name definition. The `uuid` field/');
        $this->expectException(MacroCompilationException::class);

        $this->modifier->addUuidColumn('uuid', 'uuid');
    }
}
