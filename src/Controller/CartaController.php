<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Controller\LeEscreveCsv;
use Bcca2\Steam\Model\Carta;

class CartaController extends LeEscreveCsv
{
    protected array $listaCartas = [];
    private string $path_loja;
    public function __construct()
    {
        $this->path = dirname(__DIR__) . '\components\cartasJogos.csv';
        $this->path_loja = dirname(__DIR__) . '\components\cartasLoja.csv';
        $this->PreencherObj();
    }

    public function GetPathLoja(){
        return $this->path_loja;
    }

    public function GetListaCartas():array{
        return $this->listaCartas;
    }

    public function GetListaCartasLoja(): array{
        $handleRead = fopen($this->path_loja, "r");

        while (($carta = fgetcsv($handleRead)) !== false) {
            $infoCarta = array("id_carta" => $carta[0], "id_usuario" => $carta[1], "preco" => $carta[2]);
            $newCartasLoja[] = $infoCarta;
        }
        return $newCartasLoja;
    }

    public function IsCartaOnList($carta_id) : bool{
        foreach($this->listaCartas as $carta){
            if($carta->id == $carta_id)
                return true;
        }
        return false;
    }
    
    public function GetCartaById($carta_id) : ?Carta{
        foreach($this->listaCartas as $carta){
            if($carta->id == $carta_id)
                return $carta;
        }
        return NULL;
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
