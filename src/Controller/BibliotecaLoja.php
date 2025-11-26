<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Controller\Biblioteca;
use Bcca2\Steam\Enum\StatusDb;
use Bcca2\Steam\Enum\StatusLoja;
use Bcca2\Steam\Model\Jogo;
use Bcca2\Steam\Model\JogoLoja;

class BibliotecaLoja extends Biblioteca
{
    public function __construct()
    {
        $this->path = dirname(__DIR__) . '\components\BibliotecaLoja.csv';

        if (file_exists($this->path)) {
            $this->PreencherObj();
        } else {
            //CreateCsv();
        }
    }

    protected function PreencherObj(): void
    {
        $handleRead = fopen($this->path, "r");

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleRead);

        $cartasJogo = array();

        $pathCartasLojas = dirname(__DIR__) . '\components\cartasJogos.csv';
        $handlerGetCartas = fopen($pathCartasLojas, "r");

        while (($row = fgetcsv($handleRead)) !== false) {
            $infoJogo = [
                "id" => $row[0],
                "nome" => $row[1],
                "descricao" => $row[2],
                "data_lancamento" => $row[3],
                "desenvolvedora" => $row[4],
                "distribuidora" => $row[5],
                "genero" => $row[6],
                "conquistas" => $row[7],
                "preco" => $row[8],
                "quantidade_de_analises_positivas" => $row[9],
                "quantidade_de_analises_negativas" => $row[10],
            ];

            $idCarta = $row[11];

            //CARTAS:
            while ((($carta = fgetcsv($handlerGetCartas)) !== false)) {
                if ($idCarta == $infoJogo["id"]) {
                    $cartasJogo[] = $carta;
                }
            }
            //

            $jogoLoja = new JogoLoja($infoJogo["id"], $infoJogo["nome"], $infoJogo["descricao"], $infoJogo["data_lancamento"], $infoJogo["desenvolvedora"], $infoJogo["distribuidora"], $infoJogo["genero"], $infoJogo["conquistas"], $infoJogo["preco"], $infoJogo["quantidade_de_analises_positivas"], $infoJogo["quantidade_de_analises_negativas"], $cartasJogo);

            array_push($this->jogos, $jogoLoja);
        }

        fclose($handlerGetCartas);
        fclose($handleRead);
    }

    public function adicionarNovoJogo(array $infoJogo)
    {
        if (file_exists($this->path)) {
            $idJogo = ["id" => $infoJogo[0]];

            if ($this->IsInCsv($idJogo["id"])) {
                echo "\n!!!Jogo ja incluso na loja!!!\n";
            } else {
                if ($this->UpdateCsv($infoJogo)) {
                    echo "\n!!!Jogo adicionado a loja com sucesso!!!\n";

                    $this->jogos = array();

                    $this->PreencherObj();
                } else {
                    echo "\n!!!Algo de errado ao adicionar o jogo na loja!!!\n";
                }
            }
        } else {
            echo "\n!!!Banco de Dados inexistente!!!Contate o menino da TI!!!\n";
        }
    }

    public function GetJogoById(int $id): JogoLoja
    {
        $jogoEscolhido = false;

        foreach ($this->jogos as $jogo) {
            if ($jogo->getId() == $id) {
                $jogoEscolhido = $jogo;
            }
        }
        if (!$jogoEscolhido) {
            echo "\n!!!Nenhum jogo encontrado com as informacoes passadas!!!\n";
        }
        return $jogoEscolhido;
    }
}
