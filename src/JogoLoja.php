<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Jogos;

class JogosLoja extends Jogos{
    private $preco;
    private $especificacoes;
    private $imagens;
    private $quantidade_de_analises_positivas;
    private $quantidade_de_analises_negativas;

    public function GetPreco(){
        return $this->preco;
    }

    public function GetPorcentagemDeAnalisePositiva(){
        $quantidade_total = $this->quantidade_de_analises_positivas + $this->quantidade_de_analises_negativas;
        return ($this->quantidade_de_analises_positivas * 100) / $quantidade_total; 
    }
}