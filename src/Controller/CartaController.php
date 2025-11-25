<?php

namespace Bcca2\Steam\Model;

use Bcca2\Steam\Controller\LeEscreveCsv;
use Bcca2\Steam\Model\Carta;
class CartaController extends LeEscreveCsv{
    protected array $listaCartas = [];

    public function __construct($path){
        $this->PreencherObj();
    }

    protected function PreencherObj(): void{
        $handleCartaData = fopen($this->path, "r");

        while (($carta = fgetcsv($handleCartaData)) !== false) {
                $infoCarta = array("id" => $carta[0], "idJogo" => $carta[1], "nome" => $carta[2]);
                array_push($this->listaCartas, new Carta($infoCarta["id"], $infoCarta["idJogo"], $infoCarta["nome"]));
        }
        fclose($handleCartaData);
    }

    public function adicionarCarta(carta $carta){
        $this->listaCartas [] = $carta;
        $novaCarta = ["id" => $carta->getId(), "idJogo" => $carta->getIdJogo(), "nome" => $carta->getNome()];
        $this->UpdateCsv($novaCarta, $this->path);
    }

    public function getCartasIniciais($idJogo){
        $listaCartaJogoAtual = [];
        foreach($this->listaCartas as $carta){
            if($idJogo == $carta->idJogo){
                $listaCartaJogoAtual [] = $carta->id;
            }
        }
        shuffle($listaCartaJogoAtual);
        $temp = [$listaCartaJogoAtual[0], $listaCartaJogoAtual[1], $listaCartaJogoAtual[2]];
        return $temp;
    }

}