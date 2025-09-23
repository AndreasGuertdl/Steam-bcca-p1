<?php

require_once 'vendor/autoload.php';

use Bcca2\Steam\BancoUsuarios;
use Bcca2\Steam\Menu;
use Bcca2\Steam\BibliotecaUsuario;
use Bcca2\Steam\BibliotecaLoja;
use Bcca2\Steam\JogoLoja;

$bancoUsuarios = new BancoUsuarios;
$bibliotecaUsuario = new BibliotecaUsuario;
$bibliotecaLoja = new BibliotecaLoja;
$menu = new Menu;
$statusLogin = false;
$opcaoTelaLogin = 0;
$opcaoTelaMenuPrincipal = 0;


$dragonsDogma = new JogoLoja("Dragon's Dogma", "Talvez o melhor jogo ja criado.", "2010", "Capcom", "Jesus Cristo", "Action-RPG", "42", 60);
$hollowKnight = new JogoLoja("Hollow Knight", "O pior jogo ja feito.", "1800", "Team Ladybug", "Team Ladybug", "Walking Simulator", "26", 1);
$bibliotecaLoja->adicionarJogo($dragonsDogma);
$bibliotecaLoja->adicionarJogo($hollowKnight);

while($opcaoTelaLogin != 3 && $opcaoTelaMenuPrincipal != 5){
    //Loop para login
    do{
        $menu->PrintarMenuInicial();
        $opcaoTelaLogin = (int)readline();
        switch($opcaoTelaLogin){
            case 1:
                $bancoUsuarios->RegistrarUsuarios();
                break;
            case 2:
                $statusLogin = $bancoUsuarios->Logar();
                break;
            case 3:
                echo "\nAdeus meu camarada tenha um bom dia.\n";
                break;
        }
    }while($opcaoTelaLogin != 3 && $statusLogin == false);

    //Loop para continuar na conta
    if($statusLogin){
        do{
            $menu->PrintarMenuPrincipal();
            $opcaoTelaMenuPrincipal = (int)readline();

            switch($opcaoTelaMenuPrincipal){
                case 1:
                    $menu->ControlarFluxoBiblioteca($bibliotecaUsuario);
                    break;
                case 2:
                    $menu->ControlarFluxoLoja($bibliotecaLoja, $bibliotecaUsuario, $bancoUsuarios->GetCurrentUser());
                    break;
                case 3:
                    $menu->ControlarFluxoUsuario($bancoUsuarios->GetCurrentUser());
                    break;
                case 4:
                    $statusLogin = false;
                    break;
                case 5:
                    echo "\nAdeus meu camarada tenha um bom dia.\n";
                    break;
            }  
        }while($opcaoTelaMenuPrincipal != 5 && $opcaoTelaMenuPrincipal != 4);
    }
}
?>