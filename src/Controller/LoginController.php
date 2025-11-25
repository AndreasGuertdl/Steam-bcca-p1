<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Model\Usuario;
use Bcca2\Steam\Controller\LeEscreveCsv;
use Bcca2\Steam\Model\UserDev;

class LoginController extends LeEscreveCsv
{
    private Usuario $currentUser;

    private UserDev $userDev;

    private $listaUsuarios = array();

    public function __construct()
    {
        $this->path = dirname(__DIR__) . '\components\usersLogin.csv';

        if (file_exists($this->path)) {
            $this->PreencherObj();
        } else {
            //CreateCsv();
        }
    }

    protected function PreencherObj(): void
    {
        $handle = fopen($this->path, "r");

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handle);

        while (($user = fgetcsv($handle)) !== false) {
            $infoUsuario = array("id" => $user[0], "username" => $user[1], "senha" => $user[2]);
            array_push($this->listaUsuarios, new Usuario($infoUsuario["id"], $infoUsuario["username"], $infoUsuario["senha"]));
        }

        fclose($handle);
    }

    public function GetCurrentUser(): Usuario
    {
        return $this->currentUser;
    }

    public function GetCurrentDev(): UserDev
    {
        return $this->userDev;
    }

    public function RegistrarUsuario(string $username, string $senha): bool
    {
        if ($username == null || strlen($username) < 3 || strlen($username) > 12) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
            return false;
        }
        if ($senha == null || strlen($senha) < 3 || strlen($senha) > 12) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
            return false;
        }

        if (file_exists($this->path)) {
            if ($this->IsInCsv($username)) {
                echo "\n!!!Informacoes de login ja utilizadas!!!\n";
                return false;
            }

            $novoUsuario = [substr(uniqid(), 5, 12), $username, $senha];

            if ($this->UpdateCsv($novoUsuario)) {
                echo "\n!!!Usuario criado com sucesso!!!\n";

                $this->listaUsuarios = array();
                $this->PreencherObj();

                return true;
            } else {
                echo "\n!!!Nao conseguimos criar o seu usuario!!!\n";
                return false;
            }
        } else {
            echo "\n!!!Nao conseguimos criar o seu usuario!!!\n";
            echo "\n!!!Banco de Dados inexistente!!!Contate o menino da TI!!!\n";
            return false;
        }
    }

    public function RegistrarDev(string $username, string $senha, string $publisherName): bool
    {
        if ($username == null || strlen($username) < 3 || strlen($username) > 12) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
            return false;
        }
        if ($senha == null || strlen($senha) < 3 || strlen($senha) > 12) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
            return false;
        }
        if ($publisherName == null || strlen($publisherName) < 3 || strlen($publisherName) > 12) {
            echo "\n!!!Informacoes de Nome de Desenvolvedor invalidas para Registro!!!\n";
            return false;
        }

        $pathDev = dirname(__DIR__) . '\components\devsLogin.csv';
        if (file_exists($pathDev)) {
            if ($this->IsInCsv($username, $pathDev)) {
                echo "\n!!!Informacoes de login ja utilizadas!!!\n";
                return false;
            }

            $novoDev = [substr(uniqid(), 5, 12), $username, $senha];

            if ($this->UpdateCsv($novoDev, $pathDev)) {
                echo "\n!!!Usuario criado com sucesso!!!\n";

                $this->listaUsuarios = array();
                $this->PreencherObj();

                return true;
            } else {
                echo "\n!!!Nao conseguimos criar o seu usuario de desenvolvedor!!!\n";
                return false;
            }
        } else {
            echo "\n!!!Nao conseguimos criar o seu usuario de desenvolvedor!!!\n";
            echo "\n!!!Banco de Dados inexistente!!!Contate o menino da TI!!!\n";
            return false;
        }
    }



    public function Logar(string $loginUsuario, string $loginSenha, bool $Desenvolvedor): bool
    {
        if ($loginUsuario == null || $loginSenha == null) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
            return false;
        }
        if ($Desenvolvedor){
            $pathDev = dirname(__DIR__) . '\components\devsLogin.csv';

            if(file_exists($pathDev)){
                $handle = fopen($pathDev, "r");
                fgetcsv($handle, 0, ",", "\\"); // Pular header

                while (($dev = fgetcsv($handle, 0, ",", "\\")) !== false) {
                    if ($dev[1] == $loginUsuario && $dev[2] == $loginSenha) {
                        $this->currentUser = new Usuario($dev[0], $dev[1], $dev[2]);

                        echo "\n!!!Login efetuado com sucesso.\nSeja Bem-Vindo Desenvolvedor!!!";

                        fclose($handle);
                        return true;
                    }
                }
                fclose($handle);
            }
            echo"\n!!!Nenhum desenvolvedor com estas informacoes de Login!!!\n";
            return false;
        } else {

            foreach ($this->listaUsuarios as $usuario) {
                if ($usuario->GetUsername() == $loginUsuario && $usuario->getSenha() == $loginSenha) {
                    $this->currentUser = $usuario;

                    echo "\n!!!Login efetuado com sucesso.\nSeja Bem-Vindo!!!";

                    return true;
                }
            }

            echo "\n!!!Nenhum usuario com estas informacoes de Login!!!\n";
            return false;
        }
    }
    public function LogarDev(string $loginUsuario, string $loginSenha): bool
    {
        if ($this->Logar($loginUsuario, $loginSenha, true)){
            $this->userDev = new UserDev($this->currentUser->GetUserId(), $this->currentUser->GetUsername(), $this->currentUser->getSenha(), "");

             echo "\n!!!Login efetuado com sucesso.\nSeja Bem-Vindo Desenvolvedor!!!";

            return true;
        }        
        return false;
   }
}