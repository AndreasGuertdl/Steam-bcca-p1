<?php

require_once 'vendor/autoload.php';

use Bcca2\Steam\Controller\DevController;
use Bcca2\Steam\Controller\LoginController;
use Bcca2\Steam\Controller\UsuarioController;
use Bcca2\Steam\Controller\MenuController;
use Bcca2\Steam\Controller\BibliotecaLoja;
use Bcca2\Steam\Controller\CartaController;
use Bcca2\Steam\Model\Carta;

$bancoUsuarios = new LoginController;
$menu = new MenuController;
$bibliotecaLoja = new BibliotecaLoja;
$cartaController = new CartaController("oiii");

/* $dragonsDogma = [1, "Dragon's Dogma", "Talvez o melhor jogo ja criado.", "2010", "Capcom", "Jesus Cristo", "Action-RPG", "42", 60, 100, 1];
$hollowKnight = [2, "Hollow Knight", "O pior jogo ja feito.", "1800", "Team Ladybug", "Team Ladybug", "Walking Simulator", "26", 1, 1, 1000];
$bibliotecaLoja->adicionarJogoCsv($dragonsDogma);
$bibliotecaLoja->adicionarJogoCsv($hollowKnight); */

/* $carta1 = new Carta("Mercedez", 1, 1);
$cartaController->adicionarCarta($carta1);
$carta2 = new Carta("Dragao", 2, 1);
$cartaController->adicionarCarta($carta2); */

//Loop para rodar a aplicacao
while (true) {
    $opcaoTelaLogin = 0;
    $opcaoTelaMenuPrincipal = 0;
    $loginStatusUser = false;
    $loginStatusDev = false;

    //Loop para login
    do {
        $menu->PrintarMenuInicial();
        $opcaoTelaLogin = (int)readline();

        switch ($opcaoTelaLogin) {
            case 1:
                echo "\n1- Registro de Usuario \n2- Registro de Desenvolvedor\nSelecione uma das opcoes acima: ";
                $tipoRegistro = (int)readline();
                if ($tipoRegistro === 1) {
                    $informacoesUsuario = $menu->ColetarInfoNovoUsario();
                    $bancoUsuarios->RegistrarUsuario($informacoesUsuario["username"], $informacoesUsuario["senha"]);
                } elseif ($tipoRegistro === 2) {
                    $informacoesUsuario = $menu->ColetarInfoNovoDev();
                    $bancoUsuarios->RegistrarDev($informacoesUsuario["username"], $informacoesUsuario["publisher_name"], $informacoesUsuario["senha"]);
                } else {
                    echo "\nOpção inválida. Por favor, tente novamente.\n";
                }
                break;
            case 2:
                echo "\n1- Login de Usuario \n2- Login de Desenvolvedor\nSelecione uma das opcoes acima: ";
                $tipoLogin = (int)readline();
                if ($tipoLogin === 1) {
                    $informacoesUsuario = $menu->coletarInfoParaLogin();
                    $loginStatus = $bancoUsuarios->Logar($informacoesUsuario["username"], $informacoesUsuario["senha"]);
                    $loginStatusUser = $loginStatus;
                } elseif ($tipoLogin === 2) {
                    $informacoesUsuario = $menu->coletarInfoParaLoginDev();
                    $loginStatus = $bancoUsuarios->LogarDev($informacoesUsuario["username"], $informacoesUsuario["senha"]);
                    $loginStatusDev = $loginStatus;
                } else {
                    echo "\nOpção inválida. Por favor, tente novamente.\n";
                }
                break;
            case 3:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                exit();
        }

    } while (!$loginStatusUser && !$loginStatusDev);

    $usuarioController = new UsuarioController($bancoUsuarios->GetCurrentUser());
    $devController = null;

    if ($loginStatusDev) {
        $devController = new DevController($bancoUsuarios->GetCurrentDev());
    }

    while ($loginStatusDev) {
        $menu->PrintarMenuDev();
        $opcaoTelaMenuDev = (int)readline();

        switch ($opcaoTelaMenuDev) {
            case 1:
                $menu->ControlarFluxoDev($devController->GetCurrentUser());
                break;
            case 2:
                $loginStatusDev = false;
                break;
            case 3:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                exit();
        }
    }
    //Loop para continuar na conta
    while ($loginStatusUser) {
        $menu->PrintarMenuPrincipal();
        $opcaoTelaMenuPrincipal = (int)readline();

        switch ($opcaoTelaMenuPrincipal) {
            case 1:
                $menu->ControlarFluxoBiblioteca($bancoUsuarios->GetCurrentUser()->GetUserBiblioteca());
                break;
            case 2:
                $menu->ControlarFluxoLoja($bibliotecaLoja, $bancoUsuarios->GetCurrentUser()->GetUserBiblioteca(), $usuarioController, $cartaController);
                break;
            case 3:
                $menu->ControlarFluxoUsuario($usuarioController);
                break;
            case 4:
                $loginStatusUser = false;
                break;
            case 5:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                exit();
        }
    }
}
