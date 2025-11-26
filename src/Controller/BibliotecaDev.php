<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Controller\Biblioteca;
use Bcca2\Steam\Controller\BibliotecaLoja;
use Bcca2\Steam\Model\Jogo;
use Bcca2\Steam\Model\JogoBiblioteca;
use Bcca2\Steam\Model\JogoLoja;
use Bcca2\Steam\Model\Usuario;
use Bcca2\Steam\Model\UserDev;

class BibliotecaDev extends Biblioteca
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

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleRead);

        while (($row = fgetcsv($handleRead)) !== false) {
            $idUsuario = $row[1];

            if ($idUsuario == $this->idUsuario) {
                $infoJogoBiblioteca = ["id_biblioteca" => $row[0], "id_usuario" => $idUsuario, "nome" => $row[2], "descricao" => $row[3], "data_de_lancamento" => $row[4], "desenvolvedora" => $row[5], "distribuidora" => $row[6], "genero" => $row[7], "conquistas" => $row[8], "horas_jogadas" => $row[9], "conquistas_feitas" => $row[10]];

                $jogoBiblioteca = new JogoBiblioteca($infoJogoBiblioteca["id_biblioteca"], $infoJogoBiblioteca["nome"], $infoJogoBiblioteca["descricao"], $infoJogoBiblioteca["data_de_lancamento"], $infoJogoBiblioteca["desenvolvedora"], $infoJogoBiblioteca["distribuidora"], $infoJogoBiblioteca["genero"], $infoJogoBiblioteca["conquistas"],  $infoJogoBiblioteca["id_usuario"], $infoJogoBiblioteca["horas_jogadas"], $infoJogoBiblioteca["conquistas_feitas"]);

                array_push($this->jogos, $jogoBiblioteca);
            }
        }

        fclose($handleRead);
    }

    public function publicarjogo(array $infoJogo) : bool
    {
        $jogoLoja = new JogoLoja($infoJogo[0], $infoJogo[1], $infoJogo[2], $infoJogo[3], $infoJogo[4], $infoJogo[5], $infoJogo[6], $infoJogo[7], $infoJogo[8], 0, 0);
        $pathCsvLoja = dirname(__DIR__) . '\components\BibliotecaLoja.csv';

        $jogoLoja = $this->getCsvRowById($idJogo, $pathCsvLoja);

    
        if (file_exists($this->path)) {
            $idJogo = ["id" => $infoJogo[0]];

            if ($this->IsInCsv($idJogo["id"])) {
                echo "\n!!!Jogo ja incluso na loja!!!\n";
            } else {
                if ($this->UpdateCsv($infoJogo)) {
                    echo "\n!!!Jogo adicionado a loja com sucesso!!!\n";

                    $this->jogos [] = $jogoLoja;

                    //$this->PreencherObj();
                } else {
                    echo "\n!!!Algo de errado ao adicionar o jogo na loja!!!\n";
                }
            }
        } else {
            echo "\n!!!Banco de Dados inexistente!!!Contate o menino da TI!!!\n";
        }
    }
}