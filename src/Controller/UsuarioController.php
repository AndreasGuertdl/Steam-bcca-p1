<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Model\Usuario;
use Bcca2\Steam\Controller\LeEscreveCsv;
use Bcca2\Steam\Enum\StatusUsuario;

class UsuarioController extends LeEscreveCsv
{
    private StatusUsuario $statusUsuario;
    private Usuario $currentUser;

    public function __construct(Usuario $currentUser)
    {
        $this->path = dirname(__DIR__) . '\components\usersData.csv';

        $this->currentUser = $currentUser;

        $this->AdicionarNovoUserAoCsv();

        $this->PreencherObj();
    }

    protected function PreencherObj(): void
    {
        $handle = fopen($this->path, "r");

        while (($user = fgetcsv($handle)) !== false) {
            $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);
            if ($this->currentUser->GetUserId() == $infoUsuario["id"]) {
                $this->currentUser->SetProfileName($infoUsuario["profile_name"]);
                $this->currentUser->SetSaldo($infoUsuario["saldo"]);
                break;
            }
        }

        fclose($handle);
    }

    public function SetAsWaiting(): void
    {
        $this->statusUsuario = StatusUsuario::WAITING;
    }
    public function getStatusUsuario(): StatusUsuario
    {
        return $this->statusUsuario;
    }
    public function GetCurrentUser(): Usuario
    {
        return $this->currentUser;
    }

    public function AdicionarNovoUserAoCsv(): void
    {
        if (!$this->IsInCsv($this->currentUser->GetUserId())) {
            $user = [$this->currentUser->GetUserId(), $this->currentUser->GetProfileName(), $this->currentUser->GetSaldo()];

            $this->UpdateCsv($user);
        }
    }

    public function MudarProfileName(string $id, string $novoProfileName): void
    {
        if ($novoProfileName == null || strlen($novoProfileName) < 3 || strlen($novoProfileName) > 12) {
            $this->statusUsuario = StatusUsuario::INVALIDSTRING;
        } else {
            $handleRead = fopen($this->path, "r");
            $newUserCsv = array();

            //Precisa estar aqui para pular a primeira linha (header)
            fgetcsv($handleRead);

            while (($user = fgetcsv($handleRead)) !== false) {
                $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);

                if ($infoUsuario["id"] == $id) {
                    $infoUsuario["profile_name"] = $novoProfileName;
                }

                $newUserCsv[] = $infoUsuario;
            }

            fclose($handleRead);

            $handleWrite = fopen($this->path, "w");

            $header = ['id', 'profile_name', 'saldo', 'id_biblioteca', 'id_lista_amigos', 'id_lista_cartas'];
            fputcsv($handleWrite, array_values($header));

            foreach ($newUserCsv as $user) {
                fputcsv($handleWrite, array_values($user));
            }

            $this->statusUsuario = StatusUsuario::PROFILEATUALIZADO;

            fclose($handleWrite);
        }
    }

    public function AumentarSaldo(string $id, float $valor)
    {
        if (gettype($valor) != "double" && $valor > 0) {
            $this->statusUsuario = StatusUsuario::INVALIDVALUE;
        } else {
            $path = $this->path;
            $handleRead = fopen($path, "r");
            $newUserCsv = array();

            //Precisa estar aqui para pular a primeira linha (header)
            fgetcsv($handleRead);

            while (($user = fgetcsv($handleRead)) !== false) {
                $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);

                if ($infoUsuario["id"] == $id) {
                    $infoUsuario["saldo"] += $valor;
                }

                $newUserCsv[] = $infoUsuario;
            }

            fclose($handleRead);

            $handleWrite = fopen($path, "w");

            $header = ['id', 'profile_name', 'saldo', 'id_biblioteca', 'id_lista_amigos', 'id_lista_cartas'];
            fputcsv($handleWrite, array_values($header));

            foreach ($newUserCsv as $user) {
                fputcsv($handleWrite, array_values($user));
            }

            $this->statusUsuario = StatusUsuario::SALDOATUALIZADO;

            fclose($handleWrite);
        }
    }

    public function debitarSaldo(string $id, float $valor)
    {
        $path = $this->path;
        $handleRead = fopen($path, "r");
        $newUserCsv = array();

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleRead);

        while (($user = fgetcsv($handleRead)) !== false) {
            $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);

            if ($infoUsuario["id"] == $id) {
                $infoUsuario["saldo"] += $valor;
            }

            $newUserCsv[] = $infoUsuario;
        }

        fclose($handleRead);

        $handleWrite = fopen($path, "w");

        $header = ['id', 'profile_name', 'saldo', 'id_biblioteca', 'id_lista_amigos', 'id_lista_cartas'];
        fputcsv($handleWrite, array_values($header));

        foreach ($newUserCsv as $user) {
            fputcsv($handleWrite, array_values($user));
        }

        $this->statusUsuario = StatusUsuario::SALDOATUALIZADO;

        fclose($handleWrite);
    }
}
