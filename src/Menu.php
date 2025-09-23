<?php

namespace Bcca2\Steam;

use Bcca2\Steam\Usuario;

class Menu {
    protected $opcaoMenu;

    public function PrintarMenuInicial() : void {
        echo "\nSeja Bem vindo!\n1- Registar.\n2- Logar.\n3- Sair.\nSelecione uma das opcoes acima: ";
    }

    public function PrintarMenuPrincipal() : void {
        echo "\n1- Biblioteca.\n2- Loja.\n3- Usuario.\n4- Desloggar.\n5- Sair.\nSelecione uma das opcoes acima: ";
    }

    public function PrintarMenuUsuario() : void {
        echo "\n1- Adicionar Saldo.\n2- Alterar Username.\n3- Alterar Senha.\n4- Voltar.";
    }

    public function PrintrarMenuBiblioteca() : void {
        echo "\n1- Sua Biblioteca.\n2- Voltar.";
    }

    public function PrintarMenuLoja() : void {
        echo "\n1- Jogos da Loja.\n2- Comprar Jogo.\n3- Voltar.";
    }

    public function ControlarFluxoUsuario(Usuario $usuarioLogado) : void {
        do{
            $this->printarMenuUsuario();
            echo $usuarioLogado . "\nSelecione uma das opcoes acima: ";
            $this->opcaoMenu = (int)readline();
            switch($this->opcaoMenu){
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
        }while($this->opcaoMenu != 4);
    }

    public function ControlarFluxoBiblioteca(BibliotecaUsuario $bibliotecaUsuario){
        do{
            $this->PrintrarMenuBiblioteca();
            echo "\nSelecione uma das opcoes acima: ";
            $this->opcaoMenu = (int)readline();
            if($this->opcaoMenu == 1){
                $bibliotecaUsuario->listarJogos();
            }
        }while($this->opcaoMenu != 2);
    }

    public function ControlarFluxoLoja(BibliotecaLoja $bibliotecaLoja, BibliotecaUsuario $bibliotecaUsuario, Usuario $usuario){
        do{
            $this->PrintarMenuLoja();
            echo "\nSelecione uma das opcoes acima: ";
            $this->opcaoMenu = (int)readline();
            switch($this->opcaoMenu){
                case 1:
                    $bibliotecaLoja->listarJogos();
                    break;
                case 2:
                    $bibliotecaLoja->listarJogos();
                    echo "\nDigite o ID do jogo que gostaria de comprar: ";
                    $idEscolhido = (int)readline();
                    $bibliotecaUsuario->comprarJogo($bibliotecaLoja->GetJogo($idEscolhido), $bibliotecaLoja, $usuario);
                    break;
            }
        }while($this->opcaoMenu != 3);
    }
}
?>