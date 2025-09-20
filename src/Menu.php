<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Usuario;

class Menu {
    public function printarMenuInicial() : void {
        echo "\nSeja Bem vindo!\n1- Registar.\n2- Logar.\n3- Sair.\nSelecione uma das opcoes acima: ";
    }

    public function printarMenuPrincipal() : void {
        echo "\n1- Biblioteca.\n2- Loja.\n3- Usuario.\n4- Desloggar.\n5- Sair.\nSelecione uma das opcoes acima: ";
    }

    public function printarMenuUsuario() : void {
        echo "\n1- Adicionar Saldo.\n2- Alterar Username.\n3- Alterar Senha.\n4- Voltar.";
    }

    public function ControlarFluxoUsuario(Usuario $usuarioLogado) : void {
        do{
            $this->printarMenuUsuario();
            echo $usuarioLogado->__toString() . "\nSelecione uma das opcoes acima: ";
            $opcaoMenuUsuario = (int)readline();
            switch($opcaoMenuUsuario){
                case 1:
                    $usuarioLogado->AdicionarSaldo();
                    break;
                case 2:
                    $usuarioLogado->MudarUsuario();
                    break;
                case 3:
                    $usuarioLogado->MudarSenha();
                    break;
            }
        }while($opcaoMenuUsuario != 4);
    }
}

?>