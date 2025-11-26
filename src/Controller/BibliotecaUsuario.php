<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Controller\Biblioteca;
use Bcca2\Steam\Controller\BibliotecaLoja;
use Bcca2\Steam\Model\Jogo;
use Bcca2\Steam\Model\JogoBiblioteca;
use Bcca2\Steam\Model\JogoLoja;
use Bcca2\Steam\Model\Usuario;

class BibliotecaUsuario extends Biblioteca
{
    protected string $idUsuario;

    public function __construct(string $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        $this->path = dirname(__DIR__) . '\components\BibliotecaUsuarios.csv';

        if (file_exists($this->path)) {
            $this->PreencherObj();
        } else {
            //CreateCsv();
        }
    }

    protected function PreencherObj(): void
    {
        $handleRead = fopen($this->path, "r");

        $pathCartasLojas = dirname(__DIR__) . '\components\inventarioUsuarios.csv';
        $handlerGetCartas = fopen($pathCartasLojas, "r");

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleRead);

        while (($row = fgetcsv($handleRead)) !== false) {
            $idUsuario = $row[1];

            if ($idUsuario == $this->idUsuario) {

                $cartasJogo = array();

                //PEGAR CARTAS DO INVENTARIO:
                while ((($carta = fgetcsv($handlerGetCartas)) !== false)) {
                    $idUsuarioCarta = $carta[0];
                    $idJogoCarta = $carta[1];

                    if ($idJogoCarta == $row[0] && $idUsuarioCarta == $this->idUsuario) {
                        $cartasJogo[] = $carta;
                    }
                }

                $infoJogoBiblioteca = ["id_biblioteca" => $row[0], "id_usuario" => $idUsuario, "nome" => $row[2], "descricao" => $row[3], "data_de_lancamento" => $row[4], "desenvolvedora" => $row[5], "distribuidora" => $row[6], "genero" => $row[7], "conquistas" => $row[8], "horas_jogadas" => $row[9], "conquistas_feitas" => $row[10]];

                $jogoBiblioteca = new JogoBiblioteca($infoJogoBiblioteca["id_biblioteca"], $infoJogoBiblioteca["nome"], $infoJogoBiblioteca["descricao"], $infoJogoBiblioteca["data_de_lancamento"], $infoJogoBiblioteca["desenvolvedora"], $infoJogoBiblioteca["distribuidora"], $infoJogoBiblioteca["genero"], $infoJogoBiblioteca["conquistas"],  $infoJogoBiblioteca["id_usuario"], $infoJogoBiblioteca["horas_jogadas"], $infoJogoBiblioteca["conquistas_feitas"], $cartasJogo);

                array_push($this->jogos, $jogoBiblioteca);

                $cartasJogo = [];
            }
        }

        fclose($handleRead);
    }

    public function comprarJogo(string $idJogo, float $saldoUsuario): bool
    {
        $pathCsvLoja = dirname(__DIR__) . '\components\BibliotecaLoja.csv';

        $jogoLoja = $this->getCsvRowById($idJogo, $pathCsvLoja);

        //$cartasLoja = $this->getCsvRowById($idCartas);

        if ($jogoLoja != false) {
            if (!$this->IsJogoComprado($idJogo)) {
                $idJogoLoja = $jogoLoja[0];
                $precoJogoLoja = $jogoLoja[8];

                if ($saldoUsuario >= $precoJogoLoja) {
                    $infoJogoBiblioteca = ["id_biblioteca" => $idJogoLoja, "id_usuario" => $this->idUsuario, "nome" => $jogoLoja[1], "descricao" => $jogoLoja[2], "data_de_lancamento" => $jogoLoja[3], "desenvolvedora" => $jogoLoja[4], "distribuidora" => $jogoLoja[5], "genero" => $jogoLoja[6], "conquistas" => $jogoLoja[7], "horas_jogadas" => 0, "conquistas_feitas" => 0];

                    $this->UpdateCsv($infoJogoBiblioteca);

                    //CARTAS:
                    //$idCartas = $jogoLoja[12];
                    $cartasJogo = array();

                    $pathCartasLojas = dirname(__DIR__) . '\components\cartasJogos.csv';
                    $handlerGetCartas = fopen($pathCartasLojas, "r");

                    while ((($carta = fgetcsv($handlerGetCartas)) !== false)) {
                        $idJogo = $carta[1];
                        if ($idJogo == $jogoLoja) {
                            $cartasJogo[] = $carta;
                        }
                    }

                    fclose($handlerGetCartas);

                    //
                    $jogoBiblioteca = new JogoBiblioteca($infoJogoBiblioteca["id_biblioteca"], $infoJogoBiblioteca["nome"], $infoJogoBiblioteca["descricao"], $infoJogoBiblioteca["data_de_lancamento"], $infoJogoBiblioteca["desenvolvedora"], $infoJogoBiblioteca["distribuidora"], $infoJogoBiblioteca["genero"], $infoJogoBiblioteca["conquistas"], $infoJogoBiblioteca["id_usuario"], $infoJogoBiblioteca["horas_jogadas"], $infoJogoBiblioteca["conquistas_feitas"], $cartasJogo);

                    array_push($this->jogos, $jogoBiblioteca);
                    echo "\n!!!Jogo: ", $jogoBiblioteca->getNome(), " foi adicionado a sua biblioteca!!!\n";
                    return true;
                } else {
                    echo "\n!!!Saldo insuficiente para comprar este jogo.!!!\n";
                }
            } else {
                echo "\n!!!Você já possui este jogo na sua biblioteca!!!\n";
            }
        } else {
            echo "\n!!!O jogo não está disponível na Loja!!!\n";
        }

        return false;
    }

    private function IsJogoComprado(string $idJogo): bool
    {
        foreach ($this->jogos as $jogo) {
            if ($jogo->getId() == $idJogo)
                return true;
        }
        return false;
    }
}
