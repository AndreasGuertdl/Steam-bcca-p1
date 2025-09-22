<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Jogo;

class JogoBiblioteca extends Jogo{
    private float $horas_jogadas;
    private bool $conquistas_feitas = array();
    
    public function GetHorasJogadas(){
        return $this->horas_jogadas;
    }
    public function FazerConquista(int $conquista_id){
        $this->conquistas_feitas[$conquista_id] = true;
    }
    public function GetConquistasFeitas(){
        return $this->conquistas_feitas;
    }
}