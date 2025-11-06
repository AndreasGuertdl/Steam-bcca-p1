<?php

require_once 'vendor/autoload.php';

use Bcca2\Steam\Controller\LoginController;
use Bcca2\Steam\Controller\UsuarioController;
use Bcca2\Steam\Controller\MenuController;
use Bcca2\Steam\Controller\BibliotecaLoja;

/* ONDE PAREI
Precisa fazer com que o metodo PreencherObj da classe bibliotecaLoja rode novamente apos de adicionar um jogo novo ao csv
como esta agora so roda na inicializacao da classe (pq esta no construtor) */

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
                $bancoUsuarios->Logar($informacoesUsuario["username"], $informacoesUsuario["senha"]);
                break;
            case 3:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                exit();
        }
        echo $bancoUsuarios->statusLogin->GetLoginInformation();

    } while (!$bancoUsuarios->statusLogin->GetStatusLoging());

    $usuarioController = new UsuarioController($bancoUsuarios->GetCurrentUser());

    //Loop para continuar na conta
    while ($bancoUsuarios->statusLogin->GetStatusLoging()) {
        $menu->PrintarMenuPrincipal();
        $opcaoTelaMenuPrincipal = (int)readline();

        switch ($opcaoTelaMenuPrincipal) {
            case 1:
                $menu->ControlarFluxoBiblioteca($bancoUsuarios->GetCurrentUser()->GetUserBiblioteca());
                break;
            case 2:
                $menu->ControlarFluxoLoja($bibliotecaLoja, $bancoUsuarios->GetCurrentUser()->GetUserBiblioteca(), $bancoUsuarios->GetCurrentUser());
                break;
            case 3:
                $menu->ControlarFluxoUsuario($usuarioController);
                break;
            case 4:
                $bancoUsuarios->setAsDeslogado();
                break;
            case 5:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                exit();
        }
    }
}
