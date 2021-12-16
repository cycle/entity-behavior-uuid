<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;

#[Entity]
#[Uuid4(field: 'customUuid', column: 'custom_uuid')]
class Post
{
    #[Column(type: 'primary')]
    public int $id;
}
