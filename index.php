<?php

require_once 'vendor/autoload.php';

use Bcca2\Steam\BancoUsuarios;
use Bcca2\Steam\Menu;

$bancoUsuarios = new BancoUsuarios;
$menu = new Menu;
$statusLogin = false;
$opcaoTelaLogin = 0;
$opcaoTelaMenuPrincipal = 0;

while($opcaoTelaLogin != 3 && $opcaoTelaMenuPrincipal != 5){
    //Loop para login
    do{
        $menu->printarMenuInicial();
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
            $menu->printarMenuPrincipal();
            $opcaoTelaMenuPrincipal = (int)readline();

            switch($opcaoTelaMenuPrincipal){
                case 1:
                    //$biblioteca;
                    break;
                case 2:
                    //Loja;
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