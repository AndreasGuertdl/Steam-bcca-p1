<?php

namespace Bcca2\Steam\Model;

class Carta{
    protected string $nome;
    protected string $id;
    protected string $idJogo;

    public function __construct($nome, $id, $idJogo){
        $this->nome = $nome;
        $this->id = $id;
        $this->idJogo = $idJogo;
    }

    public function getNome(){
        return $this->nome;
    }

    public function getId(){
        return $this->id;
    }

    public function getIdJogo(){
        return $this->idJogo;
    }

    public function __toString(){
            echo "Nome: " . $this->nome;
    }
}