<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\SQLServer\Uuid;

// phpcs:ignore
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\Uuid\UuidTest as CommonClass;

/**
 * @group driver
 * @group driver-sqlserver
 */
class UuidTest extends CommonClass
{
    public const DRIVER = 'sqlserver';
}
