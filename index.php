<?php

require_once 'vendor/autoload.php';

use Bcca2\Steam\Controller\LoginController;
use Bcca2\Steam\Controller\UsuarioController;
use Bcca2\Steam\Controller\MenuController;
use Bcca2\Steam\Controller\BibliotecaLoja;

$bancoUsuarios = new LoginController;
$menu = new MenuController;
$bibliotecaLoja = new BibliotecaLoja;

/* $dragonsDogma = [1, "Dragon's Dogma", "Talvez o melhor jogo ja criado.", "2010", "Capcom", "Jesus Cristo", "Action-RPG", "42", 60, 100, 1];
$hollowKnight = [2, "Hollow Knight", "O pior jogo ja feito.", "1800", "Team Ladybug", "Team Ladybug", "Walking Simulator", "26", 1, 1, 1000];
$bibliotecaLoja->adicionarJogoCsv($dragonsDogma);
$bibliotecaLoja->adicionarJogoCsv($hollowKnight); */

//Loop para rodar a aplicacao
while (true) {
    $opcaoTelaLogin = 0;
    $opcaoTelaMenuPrincipal = 0;
    $loginStatus = false;

    //Loop para login
    do {
        $menu->PrintarMenuInicial();
        $opcaoTelaLogin = (int)readline();

        switch ($opcaoTelaLogin) {
            case 1:
                $informacoesUsuario = $menu->ColetarInfoNovoUsario();
                $bancoUsuarios->RegistrarUsuario($informacoesUsuario["username"], $informacoesUsuario["senha"]);
                break;
            case 2:
                $informacoesUsuario = $menu->coletarInfoParaLogin();
                $loginStatus = $bancoUsuarios->Logar($informacoesUsuario["username"], $informacoesUsuario["senha"]);
                break;
            case 3:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                exit();
        }

    } while (!$loginStatus);

    $usuarioController = new UsuarioController($bancoUsuarios->GetCurrentUser());

    //Loop para continuar na conta
    while ($loginStatus) {
        $menu->PrintarMenuPrincipal();
        $opcaoTelaMenuPrincipal = (int)readline();

        switch ($opcaoTelaMenuPrincipal) {
            case 1:
                $menu->ControlarFluxoBiblioteca($bancoUsuarios->GetCurrentUser()->GetUserBiblioteca());
                break;
            case 2:
                $menu->ControlarFluxoLoja($bibliotecaLoja, $bancoUsuarios->GetCurrentUser()->GetUserBiblioteca(), $usuarioController);
                break;
            case 3:
                $menu->ControlarFluxoUsuario($usuarioController);
                break;
            case 4:
                $loginStatus = false;
                break;
            case 5:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                exit();
        }
    }
}
