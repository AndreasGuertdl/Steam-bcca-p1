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
            }
            $rows[] = $row;
        }
        fclose($handle);

        $handle = fopen($pathCsv, "w");
        foreach ($rows as $linha) {
            fputcsv($handle, $linha, ",", "\\");
        }
        fclose($handle);
    }

}
