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

        $this->PreencherObj();

        $this->PreencherJogosPublicados();
    }

    public function GetCurrentUser(): UserDev
    {
        return $this->currentUser;
    }

    protected function PreencherObj(): void{
    $pathCsv = dirname(__DIR__) . '\components\devsLogin.csv';
    $handle = fopen($pathCsv, "r");
    fgetcsv($handle); // Pular header

    while (($row = fgetcsv($handle, 0, ",", "\\")) !== false) {
        if (count($row) >= 3) {
            $publisherName = "";
            
            if (isset($row[3]) && !empty($row[3])) {
                $publisherName = $row[3];
            }
        
            $userDev = new UserDev(
                $row[0],  // id
                $row[1],  // name
                $row[2],  // senha
                $publisherName   // publisher_name
            );
            array_push($this->listaUsuarios, $userDev);
    public function AdicionarNovoDevAoCsv(): void
    {
        if (!$this->IsInCsv($this->currentUser->GetUserId())) {
            $user = [$this->currentUser->GetUserId(), $this->currentUser->GetUsername(), $this->currentUser->GetPublisherName()];

            $this->UpdateCsv($user);
        }
        }
        fclose($handle);
    }
    public function AdicionarNovoUserAoCsv(): void{
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
    
    public function AtualizarUsernameCsv(UserDev $userDev): void{
        $pathCsv = dirname(__DIR__) . '\components\devsLogin.csv';
    
        if (!file_exists($pathCsv)) {
            return;
        }
    
        $handle = fopen($pathCsv, "r");
        $rows = array();
    
        $header = fgetcsv($handle, 0, ",", "\\");
        $rows[] = $header;

        while (($row = fgetcsv($handle, 0, ",", "\\")) !== false) {
            if ($row[0] == $userDev->GetUserId()) {
                $row[1] = $userDev->GetUsername();

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

            $this->currentUser->SetUserName($novoProfileName);

            fclose($handleWrite);
        }
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

            $header = ['id_dev', 'name', 'senha' , 'publisher_name'];
            fputcsv($handleWrite, array_values($header));

            foreach ($newUserCsv as $user) {
                fputcsv($handleWrite, array_values($user));
            }
            $rows[] = $row;
        }
        fclose($handle);

            echo "\n!!!Publisher atualizada com sucesso!!!\n";

            fclose($handleWrite);
        }
        fclose($handle);
    }

}
