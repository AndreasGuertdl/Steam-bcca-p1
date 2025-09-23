<?php

namespace Bcca2\Steam;
use Bcca2\Steam\Biblioteca;
use Bcca2\Steam\BibliotecaLoja;

class BibliotecaUsuario extends Biblioteca {
    public function adicionarJogo(Jogo $jogo) {
        if (!$this->possuiJogo($jogo)) {
            $this->jogos[] = $jogo;
            echo "\n!!!Jogo '" . $jogo->getNome() . "' adicionado à sua Biblioteca.!!!\n";
        } else {
            echo "Você já possui o jogo '" . $jogo->getNome() . "'.\n";
        }
    }   

    public function comprarJogo(JogoLoja $jogo, BibliotecaLoja $loja, Usuario $usuario) {    
        if ($loja->possuiJogo($jogo)) {
            if (!$this->possuiJogo($jogo)) {
                if($jogo->GetPreco() <= $usuario->GetSaldo()){
                    $this->adicionarJogo($jogo);
                    $usuario->DebitarValor($jogo->GetPreco());
                    echo "\n!!!Você comprou '" . $jogo->getNome() . "' por R$" . number_format($jogo->getPreco(), 2, ',', '.') . "!!!\n";
                } else{
                    echo "\n!!!Saldo insuficiente para comprar este jogo.!!!\n";
                }
            } else {
                echo "\n!!!Você já possui '" . $jogo->getNome() . "' na sua biblioteca.!!!\n";
            }
        } else {
            echo "\n!!!O jogo '" . $jogo->getNome() . "' não está disponível na Loja.!!!\n";
        }
    }
}