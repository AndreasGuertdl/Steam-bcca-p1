<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Controller\UsuarioController;
use Bcca2\Steam\Model\Usuario;

class MenuController
{
    protected $opcaoMenu;

    public function PrintarMenuInicial(): void
    {
        echo "\n===STEAM OFFLINE===\n\nSeja Bem vindo!\n1- Registar.\n2- Logar.\n3- Sair.\nSelecione uma das opcoes acima: ";
    }

    public function PrintarMenuPrincipal(): void
    {
        echo "\n\n1- Biblioteca.\n2- Loja.\n3- Usuario.\n4- Desloggar.\n5- Sair.\nSelecione uma das opcoes acima: ";
    }

    public function PrintarMenuUsuario(): void
    {
        echo "\n1- Adicionar Saldo.\n2- Alterar Username.\n3- Voltar.";
    }

    public function PrintrarMenuBiblioteca(): void
    {
        echo "\n1- Sua Biblioteca.\n2- Voltar.";
    }

    public function PrintarMenuLoja(): void
    {
        echo "\n1- Jogos da Loja.\n2- Comprar Jogo.\n3- Voltar.";
    }

    public function PrintarJogos(array $jogos): void {
        foreach($jogos as $jogo){
            echo $jogo ."\n";
        }
    }

    public function ColetarInfoNovoUsario(): array
    {
        $infoUsuario = array("username" => "", "senha" => "");
        $username = null;
        $senha = null;

        echo "\nDefina o seu username.\nEle deve conter de 3 a 12 caracteres: ";
        $username = readline();
        $infoUsuario["username"] = $username;

        echo "\nDefina a sua senha.\nEla deve conter de 3 a 12 caracteres: ";
        $senha = readline();
        $infoUsuario["senha"] = $senha;

        return $infoUsuario;
    }

    public function coletarInfoParaLogin(): array
    {
        $infoUsuario = array("username" => "", "senha" => "");
        $loginUsuario = null;
        $loginSenha = null;

        echo "\nLOGIN DE USUARIO\nUSUARIO:";
        $loginUsuario = readline();
        $infoUsuario["username"] = $loginUsuario;

        echo "\nSENHA: ";
        $loginSenha = readline();
        $infoUsuario["senha"] = $loginSenha;

        return $infoUsuario;
    }

    public function ControlarFluxoUsuario(UsuarioController $usuarioController): void
    {
        do {
            $idCurrentUser = $usuarioController->GetCurrentUser()->GetUserId();

            $usuarioController->SetAsWaiting();

            $this->printarMenuUsuario();
            echo $usuarioController->GetCurrentUser()->__toString() . "\nSelecione uma das opcoes acima: ";
            $this->opcaoMenu = (int)readline();

            switch ($this->opcaoMenu) {
                case 1:
                    echo "\nDigite a quantidade de saldo que gostaria de adicionar: ";
                    $saldo = (float) readline();
                    $usuarioController->AumentarSaldo($idCurrentUser, $saldo);
                    break;
                case 2:
                    echo "\nDigite seu novo Profile Name: ";
                    $novoProfileName = readline();
                    $usuarioController->MudarProfileName($idCurrentUser, $novoProfileName);
                    break;
            }
            echo $usuarioController->getStatusUsuario()->GetUserInformation();
        } while ($this->opcaoMenu != 3);
    }

    public function ControlarFluxoBiblioteca(BibliotecaUsuario $bibliotecaUsuario)
    {
        do {
            $this->PrintrarMenuBiblioteca();
            echo "\nSelecione uma das opcoes acima: ";
            $this->opcaoMenu = (int)readline();

            if ($this->opcaoMenu == 1) {
                $this->PrintarJogos($bibliotecaUsuario->GetJogos());
            }
        } while ($this->opcaoMenu != 2);
    }

    public function ControlarFluxoLoja(BibliotecaLoja $bibliotecaLoja, BibliotecaUsuario $bibliotecaUsuario, Usuario $usuario)
    {
        do {
            $this->PrintarMenuLoja();
            echo "\nSelecione uma das opcoes acima: ";
            $this->opcaoMenu = (int)readline();

            switch ($this->opcaoMenu) {
                case 1:
                    $this->PrintarJogos($bibliotecaLoja->GetJogos());
                    break;
                case 2:
                    $this->PrintarJogos($bibliotecaLoja->GetJogos());

                    echo "\nDigite o ID do jogo que gostaria de comprar: ";
                    $idEscolhido = (int)readline();
                    
                    $bibliotecaUsuario->comprarJogo($idEscolhido, $usuario->GetSaldo());
                    
                    break;
            }
            echo $bibliotecaLoja->statusLoja->GetLojaInformation();
        } while ($this->opcaoMenu != 3);
    }
}
