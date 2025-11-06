<?php

namespace Bcca2\Steam\Enum;

enum StatusLoja: int
{  
    case WAITING = 0;
    case UPDATED = 1;
    case ALREADYIN =2;
    case FAILUPDATE =3;
    case NOMATCH = 4;
    case EMPTY = 5;

    public function GetLojaInformation(): string
    {
        return match ($this) {
            self::WAITING => "",
            self::UPDATED => "\n!!!Jogo adicionado a loja com sucesso!!!\n",
            self::ALREADYIN => "\n!!!Jogo ja incluso na loja!!!\n",
            self::FAILUPDATE => "\n!!!Algo de errado ao adicionar o jogo na loja!!!\n",
            self::NOMATCH => "\n!!!Nenhum jogo encontrado com as informacoes passadas!!!\n",
            self::EMPTY => "\n!!!Nenhum jogo cadastrado na loja!!!\n",
        };
    }
}
