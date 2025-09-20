<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Usuario;

class BancoUsuarios {
    protected $listaUsuarios = array();
    protected Usuario $currentUser;
    
    public function GetCurrentUser() : Usuario {
        return $this->currentUser;
    }

    function RegistrarUsuarios() : void {
        echo "\nDefina o seu username: ";
        $username = readline();

        echo "\nDefina a sua senha: ";
        $senha = readline();

        $usuarioCriado = new Usuario($username, $senha);
        array_push($this->listaUsuarios, $usuarioCriado);
    }

    function Logar() : bool {
        if(count($this->listaUsuarios) == 0){
            echo "\n!!!Nenhum usuario cadastrado no sistema!!!\n";
            return false;
        }

        echo "\nLOGIN DE USUARIO\nUSUARIO: ";
        $loginUsuario = readline();

        echo "\nSENHA: ";
        $loginSenha = readline(); 

        if($this->ChecarUsuarios($loginUsuario, $loginSenha)){
            echo "\nLogin efetuado com sucesso.\nSeja Bem-Vindo, " . $this->currentUser->GetUsername() . "!\n";
            return true;
        }
        else echo "\n!!!Informacoes invalidas!!!\n";
        return false;
    }

    function ChecarUsuarios(string $username, string $senha) : bool{
        foreach($this->listaUsuarios as $usuario){
            if($username == $usuario->GetUsername() && $senha == $usuario->GetSenha()){
                $this->currentUser = $usuario;
                return true;
            }
        }
        return false;
     }    
}