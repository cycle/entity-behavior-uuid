<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Behavior\Uuid\Tests\Fixtures\Uuid;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid2;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid3;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid5;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid6;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity
 * @Uuid6
 * @Uuid2(localDomain=0, field="dce", localIdentifier="1001")
 * @Uuid3(namespace="6ba7b811-9dad-11d1-80b4-00c04fd430c8", name="https://example.com/foo", field="md5")
 * @Uuid5(namespace="6ba7b811-9dad-11d1-80b4-00c04fd430c8", name="https://example.com/foo", field="sha1")
 */
#[Entity]
#[Uuid6]
#[Uuid2(localDomain: Uuid::DCE_DOMAIN_PERSON, field: 'dce', localIdentifier: '1001')]
#[Uuid3(namespace: Uuid::NAMESPACE_URL, name: 'https://example.com/foo', field: 'md5')]
#[Uuid5(namespace: Uuid::NAMESPACE_URL, name: 'https://example.com/foo', field: 'sha1')]
class Document
{
    /**
     * @Column(type="uuid", primary=true)
     */
    #[Column(type: 'uuid', primary: true)]
    public UuidInterface $uuid;

    /**
     * @Column(type="uuid")
     */
    #[Column(type: 'uuid')]
    public UuidInterface $dce;

    /**
     * @Column(type="uuid")
     */
    #[Column(type: 'uuid')]
    public UuidInterface $md5;

    /**
     * @Column(type="uuid")
     */
    #[Column(type: 'uuid')]
    public UuidInterface $sha1;
}
