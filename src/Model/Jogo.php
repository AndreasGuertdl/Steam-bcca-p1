<?php

namespace Bcca2\Steam\Model;

abstract class Jogo {
    private string $id;
    protected string $nome;
    protected string $descricao;
    protected string $data_de_lancamento;
    protected string $desenvolvedora;
    protected string $distribuidora;
    protected string $genero;
    protected $conquistas;

    public function __construct(string $id, string $nome, string $descricao, string $data_de_lancamento, string $desenvolvedora, string $distribuidora, string $genero, $conquistas){
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->data_de_lancamento = $data_de_lancamento;
        $this->desenvolvedora = $desenvolvedora;
        $this->distribuidora = $distribuidora;
        $this->genero = $genero;
        $this->conquistas = $conquistas;      
    }
    public function getId(){
        return $this->id;
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