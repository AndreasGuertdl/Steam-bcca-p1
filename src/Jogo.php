<?php

namespace Bcca2\Steam;

abstract class Jogos {
    protected $nome;
    protected $descricao;
    protected $data_de_lancamento;
    protected $desenvolvedora;
    protected $distribuidora;
    protected $genero;
    private string $conquistas;

    public function set_informacoes($nome, $descricao, $data_de_lancamento, $desenvolvedora, $distribuidora, $genero, $conquistas){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->data_de_lancamento = $data_de_lancamento;
        $this->desenvolvedora = $desenvolvedora;
        $this->distribuidora = $distribuidora;
        $this->genero = $genero;
        $this->conquistas = $conquistas;
    }

    public function getNome(){
        return $this->nome;
    }
    public function getDescricao(){
        return $this->descricao;
    }
    public function getDataLancamento(){
        return $this->data_de_lancamento;
    }
    public function getDesenvolvedora(){
        return $this->desenvolvedora;
    }
    public function getDistribuidora(){
        return $this->distribuidora;
    }
    public function getGenero(){
        return $this->genero;
    }
    public function getConquistas(){
        return $this->conquistas;
    }
}