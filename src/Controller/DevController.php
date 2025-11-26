<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Model\UserDev;
use Bcca2\Steam\Controller\LeEscreveCsv;

class DevController extends LeEscreveCsv
{
    private UserDev $currentUser;
    private array $listaUsuarios = array();

    public function __construct(UserDev $currentUser)
    {
        $this->path = dirname(__DIR__) . '\components\devsData.csv';

        $this->currentUser = $currentUser;

        $this->AdicionarNovoDevAoCsv();

        $this->PreencherObj();

        $this->PreencherJogosPublicados();
    }

    public function GetCurrentUser(): UserDev
    {
        return $this->currentUser;
    }

    protected function PreencherObj(): void
    {
        $handle = fopen($this->path, "r");

        while (($user = fgetcsv($handle)) !== false) {
            if ($this->currentUser->GetUserId() == $user[1]) {
                $this->currentUser->SetPublisherName(($user[2]));
            }
        }
        fclose($handle);
    }

    protected function AdicionarNovoDevAoCsv(): void
    {
        if (!$this->IsInCsv($this->currentUser->GetUserId())) {
            $user = [$this->currentUser->GetUserId(), $this->currentUser->GetUsername(), $this->currentUser->GetPublisherName()];

            $this->UpdateCsv($user);
        }
    }

    public function AdicionarNovoUserAoCsv(): void
    {
        $infoUsuario = array(
            $this->currentUser->GetUserId(),
            $this->currentUser->GetUsername(),
            $this->currentUser->GetSenha(),
            $this->currentUser->GetPublisherName()
        );

        $pathCsv = dirname(__DIR__) . '\components\devsLogin.csv';
        $this->UpdateCsv($infoUsuario, $pathCsv);
    }

    public function PreencherJogosPublicados()
    {
        $bibliotecaDev = $this->currentUser->GetBibliotecaDev();
    }

    public function AtualizarUsernameCsv(string $id, string $novoUser): void
    {
        $pathCsv = dirname(__DIR__) . '\components\devsLogin.csv';

        if (!file_exists($pathCsv)) {
            return;
        }

        $handleRead = fopen($pathCsv, "r");
        $newDevLogin = array();

        while (($dev = fgetcsv($handleRead)) !== false) {
            $infoUsuario = array("id" => $dev[0], "name" => $dev[1], "senha" => $dev[2]);

            if ($infoUsuario["id"] == $id) {
                $infoUsuario["name"] = $novoUser;
            }

            $newDevLogin[] = $infoUsuario;
        }

        fclose($handleRead);

        $handleWrite = fopen($pathCsv, "w");

        foreach ($newDevLogin as $user) {
            fputcsv($handleWrite, array_values($user));
        }

        echo "\n!!!Profile atualizado com sucesso!!!\n";

        $this->currentUser->SetUserName($novoUser);

        fclose($handleWrite);
    }

    public function SetPublisherName(string $publisher_name)
    {
        if (strlen($publisher_name) <= 3 || strlen($publisher_name) >= 15) {
            echo "\n!!!Informacoes invalidas para Publisher!!!\n";
        } else {
            $this->currentUser->SetPublisherName($publisher_name);

            $handleRead = fopen($this->path, "r");

            //Precisa estar aqui para pular a primeira linha (header)
            fgetcsv($handleRead);

            while (($user = fgetcsv($handleRead)) !== false) {
                $infoUsuario = array("id_dev" => $user[0], "name" => $user[1], "publisher_name" => $user[2]);

                if ($infoUsuario["id_dev"] == $this->currentUser->GetUserId()) {
                    $infoUsuario["publisher_name"] = $publisher_name;
                }

                $newUserCsv[] = $infoUsuario;
            }

            fclose($handleRead);

            $handleWrite = fopen($this->path, "w");

            $header = ['id_dev', 'name', 'publisher_name'];
            fputcsv($handleWrite, array_values($header));

            foreach ($newUserCsv as $user) {
                fputcsv($handleWrite, array_values($user));
            }
            fclose($handleWrite);

            echo "\n!!!Publisher atualizada com sucesso!!!\n";
        }
    }
}
