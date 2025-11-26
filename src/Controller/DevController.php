<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Model\UserDev;
use Bcca2\Steam\Controller\LeEscreveCsv;

class DevController extends LeEscreveCsv
{
    private UserDev $currentUser;

    public function __construct(UserDev $currentUser)
    {
        $this->path = dirname(__DIR__) . '\components\devsData.csv';

        $this->currentUser = $currentUser;

        $this->AdicionarNovoUserAoCsv();

        $this->PreencherObj();

        $this->PreencherJogosPublicados();
    }

    protected function PreencherObj(): void
    {
        $handleUsersData = fopen($this->path, "r");

        while (($user = fgetcsv($handleUsersData)) !== false) {
            $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "publisher_name" => $user[2]);
            if ($this->currentUser->GetUserId() == $infoUsuario["id"]) {
                $this->currentUser->SetProfileName($infoUsuario["profile_name"]);
                $this->currentUser->SetPublisherName($infoUsuario["publisher_name"]);
                break;
            }
        }
        fclose($handleUsersData);
    }

    public function GetCurrentUser(): UserDev
    {
        return $this->currentUser;
    }

    public function AdicionarNovoUserAoCsv(): void
    {
        if (!$this->IsInCsv($this->currentUser->GetUserId())) {
            $user = [$this->currentUser->GetUserId(), $this->currentUser->GetProfileName(), $this->currentUser->GetPublisherName()];

            $this->UpdateCsv($user);
        }
    }

    public function PreencherJogosPublicados()
    {
        $bibliotecaDev = $this->currentUser->GetBibliotecaDev();

        $bibliotecaDev->PreencherObj();
    }

    public function MudarProfileName(string $id, string $novoProfileName): void
    {
        if ($novoProfileName == null || strlen($novoProfileName) < 3 || strlen($novoProfileName) > 12) {
            echo "\n!!!Informacoes invalidas para atualizar seu Profile Name!!!\n";
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

            echo "\n!!!Profile atualizado com sucesso!!!\n";

            $this->currentUser->SetProfileName($novoProfileName);

            fclose($handleWrite);
        }
    }

    public function AtualizarSaldo(string $id, float $valor): bool
    {
        if (!is_float($valor)) {
            echo "\n!!!Nao foi possivel atualizar seu Saldo\nValor passado invalido!!!\n";
            return false;
        } else {
            $path = $this->path;
            $handleRead = fopen($path, "r");
            $newUserCsv = array();
            $novoSaldo = 0;

            //Precisa estar aqui para pular a primeira linha (header)
            fgetcsv($handleRead);

            while (($user = fgetcsv($handleRead)) !== false) {
                $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);

                if ($infoUsuario["id"] == $id) {
                    $novoSaldo = $infoUsuario["saldo"] += $valor;

                    $infoUsuario["saldo"] = $novoSaldo;
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

            echo "\n!!!Saldo atualizado com sucesso!!!\n";

            $this->currentUser->SetSaldo($novoSaldo);

            fclose($handleWrite);

            return true;
        }
    }
}
