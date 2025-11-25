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

    private string $pathUsersDev;

    private $listaUsuariosDev = array();

    public function __construct()
    {
        $this->path = dirname(__DIR__) . '\components\usersLogin.csv';

        $this->pathUsersDev = dirname(__DIR__) . '\components\devsLogin.csv';

        if (file_exists($this->path)) {
            $this->PreencherObj();
            $this->PreencherDevsLogin();
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

    protected function PreencherDevsLogin(): void
    {
        $handleDevCsv = fopen($this->pathUsersDev, "r");

        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleDevCsv);

        while (($dev = fgetcsv($handleDevCsv)) !== false) {
            $infoDev = array("id" => $dev[0], "username" => $dev[1], "senha" => $dev[2]);
            array_push($this->listaUsuariosDev, new UserDev($infoDev["id"], $infoDev["username"], $infoDev["senha"]));
        }

        fclose($handleDevCsv);
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

    public function RegistrarDev(string $username, string $senha): bool
    {
        if ($username == null || strlen($username) < 3 || strlen($username) > 12) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
            return false;
        }
        if ($senha == null || strlen($senha) < 3 || strlen($senha) > 12) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
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

                $this->listaUsuariosDev = array();
                $this->PreencherDevsLogin();

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

    public function Logar(string $loginUsuario, string $loginSenha): bool
    {
        if ($loginUsuario == null || $loginSenha == null) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login!!!\n";
            return false;
        }

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

    public function LogarDev(string $loginUsuario, string $loginSenha): bool
    {
        if ($loginUsuario == null || $loginSenha == null) {
            echo "\n!!!Informacoes de Usuario/Senha invalidas para Login de Desenvolvedor!!!\n";
            return false;
        }

        foreach ($this->listaUsuariosDev as $dev) {
            if ($dev->GetUsername() == $loginUsuario && $dev->getSenha() == $loginSenha) {
                $this->userDev = $dev;

                echo "\n!!!Login efetuado com sucesso.\nSeja Bem-Vindo!!!";

                return true;
            }
        }

        echo "\n!!!Nenhum Desenvolvedor com estas informacoes de Login!!!\n";
        return false;
    }
}
