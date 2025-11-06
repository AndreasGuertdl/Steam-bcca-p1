<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Model\Usuario;
use Bcca2\Steam\Controller\LeEscreveCsv;
use Bcca2\Steam\Enum\StatusDb;
use Bcca2\Steam\Enum\StatusLogin;

class LoginController extends LeEscreveCsv
{
    public StatusLogin $statusLogin;
    private Usuario $currentUser;
    private $listaUsuarios = array();

    public function __construct()
    {
        $this->path = dirname(__DIR__) . '\components\usersLogin.csv';

        $this->setAsDeslogado();

        if (file_exists($this->path)) {
            $this->PreencherObj();
        } else {
            //CreateCsv();
        }

        $this->statusDb = StatusDb::CREATED;
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

    public function setAsDeslogado()
    {
        $this->statusLogin = StatusLogin::DESLOGADO;
    }

    public function RegistrarUsuario(string $username, string $senha): bool
    {
        if ($username == null || strlen($username) < 3 || strlen($username) > 12) {
            $this->statusLogin = StatusLogin::INVALID;
            return false;
        }
        if ($senha == null || strlen($senha) < 3 || strlen($senha) > 12) {
            $this->statusLogin = StatusLogin::INVALID;
            return false;
        }

        if (file_exists($this->path)) {
            if ($this->IsInCsv($username)) {
                $this->statusLogin = StatusLogin::INUSE;

                return false;
            }

            $novoUsuario = [substr(uniqid(), 5, 12), $username, $senha];

            if ($this->UpdateCsv($novoUsuario)) {
                $this->statusLogin = StatusLogin::CREATED;
                return true;
            } else {
                $this->statusLogin = StatusLogin::FAIL;
                return false;
            }
        } else {
            $this->statusLogin = StatusLogin::FAIL;
            $this->statusDb = StatusDb::INEXISTENT;
            return false;
        }
    }

    public function Logar(string $loginUsuario, string $loginSenha): bool
    {
        if ($loginUsuario == null || $loginSenha == null) {
            $this->statusLogin = StatusLogin::INVALID;
            return false;
        }

        if (file_exists($this->path)) {
            if ($this->IsListaAtualizada($this->listaUsuarios, $this->path)) {
                foreach ($this->listaUsuarios as $usuario) {
                    if ($usuario->GetUsername() == $loginUsuario && $usuario->getSenha() == $loginSenha) {
                        $this->currentUser = $usuario;

                        $this->statusLogin = StatusLogin::LOGADO;

                        return true;
                    }
                }
            } else {
                $this->statusDb = StatusDb::OUTDATED;
                $this->statusLogin = StatusLogin::FAIL;
                return false;
            }
        } else {
            $this->statusDb = StatusDb::INEXISTENT;
            $this->statusLogin = StatusLogin::FAIL;
            return false;
        }

        $this->statusLogin = StatusLogin::NOMATCH;

        return false;
    }
}
