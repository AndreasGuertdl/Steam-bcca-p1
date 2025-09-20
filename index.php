<?php

require_once 'vendor/autoload.php';

use Bcca2\Steam\BancoUsuarios;

$bancoUsuarios = new BancoUsuarios;
$statusLogin = false;
$opcaoTelaLogin;
$opcaoTelaMenuPrincipal;

while(!$statusLogin){

    //Loop para login
    do{
        echo "\nSeja Bem vindo!\n1- Registar.\n2- Logar.\n3- Sair.\nSelecione uma das opcoes acima: ";
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
    }while($opcaoTelaLogin != 3 && !($statusLogin == true));

    //Loop para continuar na conta
    if($statusLogin){
        do{
            echo "\n1- Biblioteca.\n2- Loja.\n3- Usuario.\n4- Desloggar.\n5- Sair.\nSelecione uma das opcoes acima: ";
            $opcaoTelaMenuPrincipal = (int)readline();

            switch($opcaoTelaMenuPrincipal){
                case 1:
                    //$biblioteca;
                    break;
                case 2:
                    //Loja;
                    break;
                case 3:
                    $bancoUsuarios->GetCurrentUser()->MenuUsuario();
                    break;
                case 4:
                    $statusLogin = false;
                    $opcaoTelaMenuPrincipal = 5;
                    break;
                case 5:
                    echo "\nAdeus meu camarada tenha um bom dia.\n";
                    break;
            }  
        }while($opcaoTelaMenuPrincipal != 5);
    }
}
?>