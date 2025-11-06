<?php

namespace Bcca2\Steam\Enum;

enum StatusLogin: int
{
    case DESLOGADO = 0;
    case LOGADO = 1;
    case CREATED = 2;
    case FAIL = 3;
    case INVALID = 4;
    case INUSE = 5;
    case NOMATCH = 6;

    public function GetLoginInformation(): string
    {
        return match ($this) {
            self::DESLOGADO => "",
            self::LOGADO => "\n!!!Login efetuado com sucesso.\nSeja Bem-Vindo!!!",
            self::CREATED => "\n!!!Usuario criado com sucesso!!!\n",
            self::FAIL => "\n!!!Nao conseguimos criar o seu usuario!!!\n",
            self::INVALID => "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n",
            self::INUSE => "\n!!!Informacoes de login ja utilizadas!!!\n",
            self::NOMATCH => "\n!!!Nenhum usuario com estas informacoes de Login!!!\n",
        };
    }

    public function GetStatusLoging(): bool
    {
        if ($this->value == 1) {
            return true;
        } else return false;
    }
}
