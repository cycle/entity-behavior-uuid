<?php

declare(strict_types=1);

use Cycle\Testing\Config;
use Cycle\Testing\ORM\OrmPlugin;

return Config::forPackage('cycle/entity-behavior-uuid', root: __DIR__)
    ->unit('tests/Unit')
    ->feature('tests/Feature', plugins: [new OrmPlugin()])
    ->build();
