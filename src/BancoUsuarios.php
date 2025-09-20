<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Usuario;

class BancoUsuarios {
    protected $listaUsuarios = array();
    protected Usuario $currentUser;
    
    public function GetCurrentUser() : Usuario {
        return $this->currentUser;
    }

    public function SetCurrentUser(Usuario $usuarioLogado) : void {
        $this->currentUser = $usuarioLogado;
    }

    function RegistrarUsuarios() : void {
        $username = null;
        do{
            echo "\nDefina o seu username.\nEle deve conter de 3 a 12 caracteres: ";
            $username = readline();
            if($username == null || strlen($username) < 3 || strlen($username) > 12){
                echo "\n!!!Usuario invalido!!!\nO usuario digitado deve conter de 3 a 12 caracteres.\n";
                $username = null;
            }
        }while($username == null);
        
        $senha = null;
        do{
            echo "\nDefina a sua senha.\nEla deve conter de 3 a 12 caracteres: ";
            $senha = readline();
            if($senha == null || strlen($senha) < 3 || strlen($senha) > 12){
                echo "\n!!!Senha invalida!!!\nA senha digitada deve conter de 3 a 12 caracteres.\n";
                $senha = null;
            }
        }while($senha == null);
        
        $usuarioCriado = new Usuario($username, $senha);
        array_push($this->listaUsuarios, $usuarioCriado);
    }

    function Logar() : bool {
        if(count($this->listaUsuarios) == 0){
            echo "\n!!!Nenhum usuario cadastrado no sistema!!!\n";
            return false;
        }
        $loginUsuario = null;
        do{
            echo "\nLOGIN DE USUARIO\nUSUARIO:";
            $loginUsuario = readline();
            if($loginUsuario == null){
                echo "\n!!!Digite um usuario valido!!!\n";
            }
        }while($loginUsuario == null);
        
        $loginSenha = null;
        do{
            echo "\nSENHA: ";   
            $loginSenha = readline();
            if($loginSenha == null){
                echo "\n!!!Digite uma senha valida!!!\n";
            }
        }while($loginSenha == null);
        
        if($this->ChecarUsuarios($loginUsuario, $loginSenha)){
            echo "\nLogin efetuado com sucesso.\nSeja Bem-Vindo, " . $this->currentUser->GetUsername() . "!\n";
            return true;
        }else echo "\n!!!Informacoes invalidas para Login!!!\n";
        return false;
    }

    function ChecarUsuarios(string $username, string $senha) : bool{
        foreach($this->listaUsuarios as $usuario){
            if($username == $usuario->GetUsername() && $senha == $usuario->GetSenha()){
                $this->SetCurrentUser($usuario);
                return true;
            }
        }
        return false;
     }    
}