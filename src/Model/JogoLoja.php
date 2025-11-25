<?php

namespace Bcca2\Steam\Model;

use Bcca2\Steam\Model\Jogo;

class JogoLoja extends Jogo
{
    private $preco;
    private $quantidade_de_analises_positivas;
    private $quantidade_de_analises_negativas;

    private $especificacoes;
    private $imagens;

    public function __construct($id, $nome, $descricao, $data_de_lancamento, $desenvolvedora, $distribuidora, $genero, $conquistas, $preco, $quantidade_de_analises_positivas, $quantidade_de_analises_negativas, array $cartas)
    {
        $this->preco = $preco;
        $this->quantidade_de_analises_positivas = $quantidade_de_analises_positivas;
        $this->$quantidade_de_analises_negativas = $quantidade_de_analises_negativas;
        Jogo::__construct($id, $nome, $descricao, $data_de_lancamento, $desenvolvedora, $distribuidora, $genero, $conquistas, $cartas);
    }

    public function SetSteamPage($preco, $especificacoes)
    {
        $this->preco = $preco;
        $this->especificacoes = $especificacoes;
    }

    public function GetPreco()
    {
        return $this->preco;
    }

    public function GetPorcentagemDeAnalisePositiva()
    {
        $quantidade_total = $this->quantidade_de_analises_positivas + $this->quantidade_de_analises_negativas;
        return ($this->quantidade_de_analises_positivas * 100) / $quantidade_total;
    }

    public function __toString()
    {
        return "\n|" . $this->getId() . " - $this->nome          LANCAMENTO: $this->data_de_lancamento\nDESCRICAO: $this->descricao\nGENERO: $this->genero.\nPRECO: " . number_format($this->preco, 2, ',', '.') . "R$.";
    }
}
