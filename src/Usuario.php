<?php

namespace Bcca2\Steam;
use Bcca2\Steam\BibliotecaUsuario;

class Usuario {
    private string $username;
    private string $senha;
    protected float $saldo = 0;
    private BibliotecaUsuario $biblioteca;

    function __construct(string $username, string $senha)
    {
        $this->biblioteca = new BibliotecaUsuario;
        $this->username = $username;
        $this->senha = $senha;
    }
    public function GetUsername() : string {
        return $this->username;
    }
    public function GetSenha() : string {
        return $this->senha;
    }
    public function GetSaldo() : float {
        return $this->saldo;
    }
    public function GetUserBiblioteca() : BibliotecaUsuario {
        return $this->biblioteca;
    }

    public function AdicionarSaldo() : void {
        echo "\nDigite a quantidade de saldo que gostaria de adicionar: ";
        $saldo = (float)readline();
        $this->saldo = $saldo;
        echo "\n!!!$saldo R$ adicionado(s) com sucesso!!!\n";
    }

    public function DebitarValor(int $valor) : void {
        $this->saldo -= $valor;
    }

    public function MudarUsuario() : void {
        $username = null;
        do{
            echo "\nDefina o seu novo username.\nEle deve conter de 3 a 12 caracteres: ";
            $username = readline();
            if($username == null || strlen($username) < 3 || strlen($username) > 12){
                echo "\n!!!Usuario invalido!!!\nO usuario digitado deve conter de 3 a 12 caracteres.\n";
                $username = null;
            }else{
                $this->username = $username;
            }
        }while($username == null);
    }

    public function MudarSenha() : void {
        $senha = null;
        do{
            echo "\nDefina a sua nova senha.\nEla deve conter de 3 a 12 caracteres: ";
            $senha = readline();
            if($senha == null || strlen($senha) < 3 || strlen($senha) > 12){
                echo "\n!!!Senha invalida!!!\nA senha digitada deve conter de 3 a 12 caracteres.\n";
                $senha = null;
            }else{
                $this->senha = $senha;
            }
        }while($senha == null);
    }

    public function __toString()
    {
        return "\n|USUARIO: $this->username          SALDO: $this->saldo R$|";
    }
}

?>