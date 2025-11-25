<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Controller\LeEscreveCsv;
use Bcca2\Steam\Model\Carta;

class CartaController extends LeEscreveCsv
{
    protected array $listaCartas = [];

    public function __construct()
    {
        $this->path = dirname(__DIR__) . '\components\cartasJogos.csv';
        $this->PreencherObj();
    }

    public function GetListaCartas():array{
        return $this->listaCartas;
    }

    protected function PreencherObj(): void
    {
        $handleCartaData = fopen($this->path, "r");

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleCartaData);

        while (($carta = fgetcsv($handleCartaData)) !== false) {
            $infoCarta = array("id" => $carta[0], "idJogo" => $carta[1], "nome" => $carta[2]);
            array_push($this->listaCartas, new Carta($infoCarta["id"], $infoCarta["idJogo"], $infoCarta["nome"]));
        }

        fclose($handleCartaData);
    }

    public function adicionarCarta(Carta $carta)
    {
        $this->listaCartas[] = $carta;
        $novaCarta = ["id" => $carta->getId(), "idJogo" => $carta->getIdJogo(), "nome" => $carta->getNome()];
        $this->UpdateCsv($novaCarta, $this->path);
    }

    public function getCartasIniciais($idJogo)
    {
        $listaCartaJogoAtual = [];

        foreach ($this->listaCartas as $carta) {
            //echo "\nID CARTA: ", $carta->getId(),"\nID JOGO: ", $idJogo,"\nID CARTA JOGO: ", $carta->getIdJogo(), "\n";
            
            if ($idJogo == $carta->getIdJogo()) {
                //echo "ACHEI UM JOGO PARA ESTA CARTA!";
                $listaCartaJogoAtual[] = $carta;
            }
        }

        shuffle($listaCartaJogoAtual);
        
        $temp = [$listaCartaJogoAtual[0], $listaCartaJogoAtual[1], $listaCartaJogoAtual[2]];

        return $temp;
    }
}
