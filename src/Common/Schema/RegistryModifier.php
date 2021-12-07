<?php

declare(strict_types=1);

namespace Cycle\ORM\Entity\Macros\Uuid\Common\Schema;

use Cycle\Database\Schema\AbstractColumn;
use Cycle\ORM\Entity\Macros\Common\Schema\RegistryModifier as BaseRegistryModifier;
use Cycle\ORM\Entity\Macros\Exception\MacroCompilationException;
use Cycle\ORM\Entity\Macros\Uuid\Uuid\UuidTypecast;
use Cycle\Schema\Definition\Field;

class RegistryModifier extends BaseRegistryModifier
{
    /** @throws MacroCompilationException */
    public function addUuidColumn(string $columnName, string $fieldName): AbstractColumn
    {
        if ($this->fields->has($fieldName)) {
            if (!$this->isType(self::UUID_COLUMN, $fieldName, $columnName)) {
                throw new MacroCompilationException(sprintf('Field %s must be of type uuid.', $fieldName));
            }
            $this->validateColumnName($fieldName, $columnName);
            if ($this->fields->get($fieldName)->getTypecast() === null) {
                $this->fields->get($fieldName)->setTypecast([UuidTypecast::class, 'cast']);
            }

            return $this->table->column($columnName);
        }

        $this->fields->set(
            $fieldName,
            (new Field())->setColumn($columnName)->setType('uuid')->setTypecast([UuidTypecast::class, 'cast'])
        );

        return $this->table->column($columnName)->type(self::UUID_COLUMN);
    }
}
