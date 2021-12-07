<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Macros\Uuid\Tests\Functional\Driver\SQLite\Uuid;

// phpcs:ignore
use Cycle\ORM\Entity\Macros\Uuid\Tests\Functional\Driver\Common\Uuid\UuidMacroTest as CommonClass;

/**
 * @group driver
 * @group driver-sqlite
 */
class UuidMacroTest extends CommonClass
{
    public const DRIVER = 'sqlite';
}
