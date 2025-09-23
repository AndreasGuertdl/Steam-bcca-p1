<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Jogo;

class JogoLoja extends Jogo{
    private $preco;
    private $especificacoes;
    private $imagens;
    private $quantidade_de_analises_positivas;
    private $quantidade_de_analises_negativas;

    public function __construct($nome, $descricao, $data_de_lancamento, $desenvolvedora, $distribuidora, $genero, $conquistas, $preco)
    {
        $this->preco = $preco;
        Jogo::__construct($nome, $descricao, $data_de_lancamento, $desenvolvedora, $distribuidora, $genero, $conquistas);
    }

    public function SetSteamPage($preco, $especificacoes){
        $this->preco = $preco;
        $this->especificacoes = $especificacoes;
    }
    
    public function GetPreco(){
        return $this->preco;
    }

    public function GetPorcentagemDeAnalisePositiva(){
        $quantidade_total = $this->quantidade_de_analises_positivas + $this->quantidade_de_analises_negativas;
        return ($this->quantidade_de_analises_positivas * 100) / $quantidade_total; 
    }

    public function __toString()
    {
        return "\n|".$this->getId()." - $this->nome          LANCAMENTO: $this->data_de_lancamento\nDESCRICAO: $this->descricao\nGENERO: $this->genero.\nPRECO: ".number_format($this->preco, 2, ',', '.')."R$.";
    }
}