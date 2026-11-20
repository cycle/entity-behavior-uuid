<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid1;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity
 * @Uuid1
 * @Uuid1(field="notDefinedUuid", column="not_defined_uuid", nullable=true)
 */
#[Entity]
#[Uuid1]
#[Uuid1(field: 'notDefinedUuid', column: 'not_defined_uuid', nullable: true)]
final class NullableUuid
{
    /**
     * @Column(type="uuid", primary=true)
     */
    #[Column(type: 'uuid', primary: true)]
    public UuidInterface $uuid;
}
