<div align="center">
    <a href="https://cycle-orm.dev" target="_blank">
        <picture>
            <source media="(prefers-color-scheme: dark)" srcset="https://github.com/cycle/.github/blob/main/logo/words-vector-dark.svg?raw=true">
            <img width="50%" align="center" src="https://github.com/cycle/.github/blob/main/logo/words-vector-light.svg?raw=true" alt="CycleORM Logo">
        </picture>
    </a>
</div>

<p align="center">ramsey/uuid identifiers for Cycle ORM entities</p>

<div align="center">

[![Documentation](https://img.shields.io/badge/Documentation-blue?style=for-the-badge&logo=gitbook&logoColor=white)](https://cycle-orm.dev/docs/entity-behaviors-uuid)
[![Sponsor](https://img.shields.io/static/v1?style=for-the-badge&label=&message=Sponsor&logo=githubsponsors&logoColor=white&color=%23EA4AAA)](https://github.com/sponsors/cycle)
[![Discord](https://img.shields.io/discord/538114875570913290?style=for-the-badge&logo=discord&labelColor=7289d9&logoColor=white&color=39456d)](https://discord.gg/spiralphp)

[![Psalm Level](https://shepherd.dev/github/cycle/entity-behavior-uuid/level.svg)](https://shepherd.dev/github/cycle/entity-behavior-uuid)
[![Type Coverage](https://shepherd.dev/github/cycle/entity-behavior-uuid/coverage.svg)](https://shepherd.dev/github/cycle/entity-behavior-uuid)
[![Codecov](https://codecov.io/gh/cycle/entity-behavior-uuid/branch/1.x/graph/badge.svg)](https://codecov.io/gh/cycle/entity-behavior-uuid)

</div>

<br />

The package provides an ability to use `ramsey/uuid` as a Cycle ORM entity column type.

## Get Started

### Installation

```bash
composer require cycle/entity-behavior-uuid
```

[![PHP](https://img.shields.io/packagist/php-v/cycle/entity-behavior-uuid.svg?style=flat-square&logo=php)](https://packagist.org/packages/cycle/entity-behavior-uuid)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/cycle/entity-behavior-uuid.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/cycle/entity-behavior-uuid)
[![License](https://img.shields.io/packagist/l/cycle/entity-behavior-uuid.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/cycle/entity-behavior-uuid.svg?style=flat-square)](https://packagist.org/packages/cycle/entity-behavior-uuid/stats)

Requires PHP 8.2+.

### Usage

Version 4 UUIDs are randomly generated and do not contain any information about the time they are created or the machine that
generated them.

```php
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;
use Ramsey\Uuid\UuidInterface;

#[Entity]
#[Uuid4]
class User
{
    #[Column(field: 'uuid', type: 'uuid', primary: true)]
    private UuidInterface $uuid;
}
```

You can find more information about Entity behavior UUID [here](https://cycle-orm.dev/docs/entity-behaviors-uuid).
