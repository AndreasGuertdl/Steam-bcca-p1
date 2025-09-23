<?php

namespace Bcca2\Steam;

abstract class Jogo {
    static $idGeral = 0;
    private $id;
    protected $nome;
    protected $descricao;
    protected $data_de_lancamento;
    protected $desenvolvedora;
    protected $distribuidora;
    protected $genero;
    protected $conquistas;

    public function __construct($nome, $descricao, $data_de_lancamento, $desenvolvedora, $distribuidora, $genero, $conquistas){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->data_de_lancamento = $data_de_lancamento;
        $this->desenvolvedora = $desenvolvedora;
        $this->distribuidora = $distribuidora;
        $this->genero = $genero;
        $this->conquistas = $conquistas;
        self::$idGeral++;
        $this->id = self::$idGeral;        
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
    public function __toString()
    {
        return "\n|$this->id - $this->nome          LANCAMENTO: $this->data_de_lancamento\nDESCRICAO: $this->descricao\nGENERO: $this->genero.\n";
    }
}