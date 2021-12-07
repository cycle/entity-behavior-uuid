<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Macros\Uuid\Tests\Functional\Driver\Postgres\Schema;

// phpcs:ignore
use Cycle\ORM\Entity\Macros\Uuid\Tests\Functional\Driver\Common\Schema\RegistryModifierTest as CommonClass;

/**
 * @group driver
 * @group driver-postgres
 */
class RegistryModifierTest extends CommonClass
{
    public const DRIVER = 'postgres';
}
