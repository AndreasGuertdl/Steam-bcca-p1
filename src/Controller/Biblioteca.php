<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Enum\StatusDb;
use Bcca2\Steam\Model\Jogo;
use Bcca2\Steam\Enum\StatusLoja;

abstract class Biblioteca extends LeEscreveCsv
{
    public $jogos = [];

    public function GetJogos(): array
    {
        if ($this->jogos == null) {
            echo "\n!!!Nenhum jogo comprado.!!!\n";
        }
        return $this->jogos;
    }
}
