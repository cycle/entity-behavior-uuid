<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;

/**
 * @Entity
 * @Uuid4(field="token")
 */
#[Entity]
#[Uuid4(field: 'token')]
class Comment
{
    /**
     * @Column(type="primary")
     */
    #[Column(type: 'primary')]
    public int $id;
}
