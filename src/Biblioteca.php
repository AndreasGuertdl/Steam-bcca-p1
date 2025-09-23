<? php

namespace Bcca2\Steam;

use Bcca2\Steam\Jogo;

abstract class Biblioteca {
    protected $jogos = [];

    abstract public function adicionarJogo(Jogo $jogo);

    public function listarJogos() {
        if (empty($this->jogos)) {
            echo "Nenhum jogo encontrado.\n";
        } else {
            foreach ($this->jogos as $jogo) {
                echo "- " . $jogo . "\n";
            }
        }
    }

    public function possuiJogo(Jogo $jogo) {
        foreach ($this->jogos as $j) {
            if ($j->getNome() === $jogo->getNome()) {
                return true;
            }
        }
        return false;
    }
}