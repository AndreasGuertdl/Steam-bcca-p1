<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Enum\StatusDb;

abstract class LeEscreveCsv
{
    protected string $path;

    //criarCsv()

    //Preencher um obj com informacoes do CSV
    abstract protected function PreencherObj(): void;

    //Adiciona uma nova row na ultima linha do CSV
    protected function UpdateCsv(array $data, ?string $path = null): bool
    {
        if ($path == null) {
            $handleWrite = fopen($this->path, "a");
        } else {
            $handleWrite = fopen($path, "a"); 
        }

        $status = fputcsv($handleWrite, array_values($data));

        fclose($handleWrite);

        return $status;
    }

    protected function LerCsv(?string $path = null): array
    {
        if ($path != null) {
            $handleRead = fopen($path, "r");
        } else {
            $handleRead = fopen($this->path, "r");
        }

        $csvArray = array();

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleRead);

        while (($row = fgetcsv($handleRead)) !== false) {
            array_push($csvArray, $row);
        }

        fclose($handleRead);

        return $csvArray;
    }

    protected function getCsvRowById(string $id, ?string $path = null) : array
    {
        if ($path != null) {
            $handleRead = fopen($path, "r");
        } else {
            $handleRead = fopen($this->path, "r");
        }

        while (($row = fgetcsv($handleRead)) !== false) {
            $idCsv = $row[0];

            if($id == $idCsv){
                $desiredElement = $row;
                break;
            }
        }

        fclose($handleRead);

        return $desiredElement;
    }

    protected function getCsvRowByName(string $name, ?string $path = null) : array
    {
        if ($path != null) {
            $handleRead = fopen($path, "r");
        } else {
            $handleRead = fopen($this->path, "r");
        }

        while (($row = fgetcsv($handleRead)) !== false) {
            $idCsv = $row[1];

            if($name == $idCsv){
                $desiredElement = $row;
                break;
            }
        }

        fclose($handleRead);

        return $desiredElement;
    }

    protected function IsInCsv(string $info, ?string $path = null): bool
    {
        if ($path == null) {
            $handleRead = fopen($this->path, "r");
        } else {
            $handleRead = fopen($path, "r");
        }

        while (($row = fgetcsv($handleRead)) !== false) {
            $infoCsv = ["id" => $row[0], "name" => $row[1]];

            if ($info == $infoCsv["id"] || $info == $infoCsv["name"]) {
                return true;
            }
        }

        fclose($handleRead);

        return false;
    }

    protected function IsListaAtualizada(array $lista): bool
    {
        $contador = 0;
        $handleRead = fopen($this->path, "r");

        while ((fgetcsv($handleRead)) !== false) {
            $contador++;
        }

        fclose($handleRead);

        return count($lista) == ($contador - 1);
    }
}
