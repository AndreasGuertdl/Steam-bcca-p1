<?php

namespace Bcca2\Steam;

class Usuario {
    private string $username;
    private string $senha;
    protected float $saldo = 0;

    function __construct(string $username, string $senha)
    {
        $this->username = $username;
        $this->senha = $senha;
    }

    public function GetUsername() : string {
        return $this->username;
    }

    public function GetSenha() : string {
        return $this->senha;
    }

    function MenuUsuario() : void {
        do{
            echo $this->__toString();
            echo "\n1- Adicionar Saldo.\n2- Alterar Username.\n3- Alterar Senha.\n4- Voltar.\nSelecione uma das opcoes acima: ";
            $opcaoMenuUsuario = (int)readline();

            switch($opcaoMenuUsuario){
                case 1:
                    $this->AdicionarSaldo();
                    break;
                case 2:
                    $this->MudarUsuario();
                    break;
                case 3:
                    $this->MudarSenha();
                    break;
            }
        }while($opcaoMenuUsuario != 4);
    }

    private function AdicionarSaldo() : void {
        echo "\nDigite a quantidade de saldo que gostaria de adicionar: ";
        
        $saldo = (float)readline();
        $this->saldo = $saldo;

        echo "\n!!!$saldo R$ adicionado(s) com sucesso!!!\n";
    }

    public function MudarUsuario() : void {
        echo "\nDigite o seu novo username: ";
        $this->username = readline();
    }

    public function MudarSenha() : void {
        echo "\nDigite sua nova senha: ";
        $this->senha = readline();
    }

    public function __toString()
    {
        return "\n|USUARIO: $this->username          SALDO: $this->saldo R$|";
    }
}

?>