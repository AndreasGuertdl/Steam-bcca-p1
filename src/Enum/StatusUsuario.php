<?php

namespace Bcca2\Steam\Enum;

enum StatusUsuario: int
{  
    case WAITING = 1;
    case SALDOATUALIZADO = 2;
    case PROFILEATUALIZADO = 3;
    case INVALIDVALUE = 4;
    case INVALIDSTRING = 5;

    public function GetUserInformation(): string
    {
        return match ($this) {
            self::WAITING => "",
            self::SALDOATUALIZADO => "\n!!!Saldo atualizado com sucesso!!!\n",
            self::PROFILEATUALIZADO => "\n!!!Profile atualizado com sucesso\n!!!",
            self::INVALIDSTRING => "\n!!!Informacoes invalidas para atualizar seu Profile Name\n!!!\n",
            self::INVALIDVALUE => "\n!!!Nao foi possivel atualizar seu Saldo\nValor passado invalido!!!\n",
        };
    }
}
