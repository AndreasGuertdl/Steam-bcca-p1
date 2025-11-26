<?php

namespace Bcca2\Steam\Model;

use Bcca2\Steam\Model\Jogo;

class JogoBiblioteca extends Jogo
{
    private string $idUsuario;
    private float $horas_jogadas = 0;
    private int $conquistas_feitas;

    public function __construct(string $id, string $nome, string $descricao, string $data_de_lancamento, string $desenvolvedora, string $distribuidora, string $genero, $conquistas, string $idUsuario, int $horas_jogadas, $conquistas_feitas, array $cartas)
    {
        $this->idUsuario = $idUsuario;
        $this->horas_jogadas = $horas_jogadas;
        $this->conquistas_feitas = $conquistas_feitas;
        Jogo::__construct($id, $nome, $descricao, $data_de_lancamento, $desenvolvedora, $distribuidora, $genero, $conquistas, $cartas);
    }
    public function GetUserId(): string
    {
        return $this->idUsuario;
    }
    public function GetHorasJogadas()
    {
        return $this->horas_jogadas;
    }
    public function FazerConquista(int $conquista_id)
    {
        $this->conquistas_feitas[$conquista_id] = true;
    }
    public function GetConquistasFeitas()
    {
        return $this->conquistas_feitas;
    }

    public function __toString()
    {
        return "\n| $this->nome          LANCAMENTO: $this->data_de_lancamento\nDESCRICAO: $this->descricao\nGENERO: $this->genero.\nHORAS JOGADAS: $this->horas_jogadas";
    }
}
