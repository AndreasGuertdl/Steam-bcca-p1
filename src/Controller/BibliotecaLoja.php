<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Controller\Biblioteca;
use Bcca2\Steam\Enum\StatusDb;
use Bcca2\Steam\Enum\StatusLoja;
use Bcca2\Steam\Model\Jogo;
use Bcca2\Steam\Model\JogoLoja;

class BibliotecaLoja extends Biblioteca
{
    public StatusLoja $statusLoja;
    
    public function __construct()
    {
        $this->path = dirname(__DIR__) . '\components\BibliotecaLoja.csv';

        if (file_exists($this->path)) {
            $this->PreencherObj();
        } else {
            //CreateCsv();
        }
        $this->statusLoja = StatusLoja::WAITING;
        $this->statusDb = StatusDb::CREATED;
    }

    protected function PreencherObj(): void
    {
        $handleRead = fopen($this->path, "r");

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleRead);

        while (($row = fgetcsv($handleRead)) !== false) {
            $infoJogo = ["id" => $row[0], "nome" => $row[1], "descricao" => $row[2], "data_lancamento" => $row[3], "desenvolvedora" => $row[4], "distribuidora" => $row[5], "genero" => $row[6], "conquistas" => $row[7], "preco" => $row[8], "quantidade_de_analises_positivas" => $row[9], "quantidade_de_analises_negativas" => $row[10]];
            
            $jogoLoja = new JogoLoja($infoJogo["id"], $infoJogo["nome"], $infoJogo["descricao"], $infoJogo["data_lancamento"], $infoJogo["desenvolvedora"], $infoJogo["distribuidora"], $infoJogo["genero"], $infoJogo["conquistas"], $infoJogo["preco"], $infoJogo["quantidade_de_analises_positivas"], $infoJogo["quantidade_de_analises_negativas"]);
            
            array_push($this->jogos, $jogoLoja);
        }

        fclose($handleRead);
    }

    public function adicionarNovoJogo(array $infoJogo)
    {
        if (file_exists($this->path)) {
            $idJogo = ["id"=>$infoJogo[0]];

            if ($this->IsInCsv($idJogo["id"])) {
                $this->statusLoja = StatusLoja::ALREADYIN;
            } else {
                if ($this->UpdateCsv($infoJogo)) {
                    $this->statusLoja = StatusLoja::UPDATED;

                    $this->jogos = array();

                    $this->PreencherObj();
                } else {
                    $this->statusLoja = StatusLoja::FAILUPDATE;
                }
            }
        } else {
            $this->statusDb = StatusDb::INEXISTENT;
        }
    }

    public function GetJogoById(int $id): Jogo|false
    {
        $jogoEscolhido = false;
        
        foreach ($this->jogos as $jogo) {
            if ($jogo->getId() == $id) {
                $jogoEscolhido = $jogo;
            }
        }
        if(!$jogoEscolhido){
            $this->statusLoja = StatusLoja::NOMATCH;
        }
        return $jogoEscolhido;
    }
}
