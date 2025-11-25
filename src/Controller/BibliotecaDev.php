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

    public function publicarjogo(array $infoJogo)
    {
        
        $pathCsvLoja = dirname(__DIR__) . '\components\BibliotecaLoja.csv';

        $idJogo = $infoJogo[0];

        if ($idJogo === null || $idJogo === ""){
            echo "\n!!!Informacoes invalidas para publicar o jogo na loja!!!\n";
            return;
        }

        if (file_exists($this->path)) {
            $handleRead = fopen($pathCsvLoja, "r");
            fgetcsv($handleRead);

            while (($row = fgetcsv($handleRead)) !== false) {
                if ($row[0] == $idJogo) {
                    echo "\n!!!Jogo ja publicado na loja!!!\n";
                    fclose($handleRead);
                    return;
                }
            }
        }
        $jogoLoja = new JogoLoja($infoJogo[0], $infoJogo[1], $infoJogo[2], $infoJogo[3], $infoJogo[4], $infoJogo[5], $infoJogo[6], (int)$infoJogo[7], (float)$infoJogo[8], 0, 0);
        if ($this->UpdateCsv($jogoLoja->toArray(), $pathCsvLoja)) {
            echo "\nJogo publicado com sucesso na loja!\n";
        } else {
            echo "\nFalha ao publicar o jogo na loja.\n";
        }
    }
    public function ListarJogos(): array {
        $pathCsvLoja = dirname(__DIR__) . '\components\BibliotecaLoja.csv';
        $jogosPublicados = array();

        if (file_exists($pathCsvLoja)) {
            $handleRead = fopen($pathCsvLoja, "r");
            fgetcsv($handleRead);

            while (($row = fgetcsv($handleRead)) !== false) {
                if ($row[4] == $this->idUsuario) {
                    $jogoLoja = new JogoLoja($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], (int)$row[7], (float)$row[8], (int)$row[9], (int)$row[10]);
                    array_push($jogosPublicados, $jogoLoja);
                }
            }
            fclose($handleRead);
        }
        return $jogosPublicados;
    }
}