<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\MySQL\Uuid;

// phpcs:ignore
use Cycle\ORM\Entity\Behavior\Uuid\Tests\Functional\Driver\Common\Uuid\ListenerTest as CommonClass;

/**
 * @group driver
 * @group driver-mysql
 */
class ListenerTest extends CommonClass
{
    public const DRIVER = 'mysql';
}
