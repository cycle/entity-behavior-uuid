# Entity behavior UUID 

The package provides an ability to use `ramsey/uuid` as a Cycle ORM entity column type.

## Installation

Install this package as a dependency using Composer.

```bash
composer require cycle/entity-behavior-uuid
```

## Example

They are randomly-generated and do not contain any information about the time they are created or the machine that
generated them.

```php
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior;
use Ramsey\Uuid\UuidInterface;

#[Entity]
#[Behavior\Uuid4]
class User
{
    #[Column(field: 'uuid', type: 'uuid', primary: true)]
    private UuidInterface $uuid;
}
```

You can find more information about Entity behavior UUID [here](https://cycle-orm.dev/docs/entity-behaviors-uuid).

## License:

The MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information.
Maintained by [Spiral Scout](https://spiralscout.com).
