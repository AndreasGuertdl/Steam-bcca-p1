<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Biblioteca;

class BibliotecaLoja extends Biblioteca {
    public function adicionarJogo(Jogo $jogo) {
        if (!$this->possuiJogo($jogo)) {
            array_push($this->jogos, $jogo);
            echo "Jogo '" . $jogo->getNome() . "' adicionado à Loja.\n";
        } else {
            echo "O jogo '" . $jogo->getNome() . "' já existe na Loja.\n";
        }
    }

    public function GetJogo(int $id) : Jogo{
        foreach($this->jogos as $jogo){
            if($jogo->getId() == $id){
                $jogoEscolhido = $jogo;
            }
        }
        return $jogoEscolhido;
    }
}