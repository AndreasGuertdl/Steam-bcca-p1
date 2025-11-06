<?php

namespace Bcca2\Steam\Enum;

enum StatusDb: int
{
    case INEXISTENT = 0;
    case CREATED = 1;
    case UPDATED = 2;
    case OUTDATED = 3;

    public function getStatusDb(): string
    {
        return match ($this) {
            self::INEXISTENT => "\n!!!Db inexistente!!!\n",
            self::CREATED => "\n!!!Db criada!!!\n",
            self::UPDATED => "\n!!!Db atualizada!!!\n",
            self::OUTDATED => "\n!!!Lista de objetos desatualizada!!!\n",
        };
    }

    public function isDbOk(): bool
    {
        return match ($this) {
            self::CREATED => true,
            self::UPDATED => true,
            self::INEXISTENT => false,
            self::OUTDATED => false,
        };
    }
}
