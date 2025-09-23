<? php

namespace Bcca2\Steam;
use Bcca2\Steam\Biblioteca;

class Biblioteca_Usuario extends Biblioteca {
    public function adicionarJogo(Jogo $jogo) {
        if (!$this->possuiJogo($jogo)) {
            $this->jogos[] = $jogo;
            echo "Jogo '" . $jogo->getNome() . "' adicionado à sua Biblioteca.\n";
        } else {
            echo "Você já possui o jogo '" . $jogo->getNome() . "'.\n";
        }
    }   

    public function comprarJogo(Jogo $jogo, Biblioteca_Loja $loja) {
        if ($loja->possuiJogo($jogo)) {
            if (!$this->possuiJogo($jogo)) {
                $this->adicionarJogo($jogo);
                echo "Você comprou '" . $jogo->getNome() . "' por R$" . number_format($jogo->getPreco(), 2, ',', '.') . "\n";
            } else {
                echo "Você já possui '" . $jogo->getNome() . "' na sua biblioteca.\n";
            }
        } else {
            echo "O jogo '" . $jogo->getNome() . "' não está disponível na Loja.\n";
        }
    }
}