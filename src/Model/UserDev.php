<?php

namespace Bcca2\Steam\Model;

use Bcca2\Steam\Controller\BibliotecaDev;

class Usuario
{
    private string $id;
    private string $username;
    private string $publisher_name;
    private string $senha;
    private BibliotecaDev $biblioteca;

    function __construct(string $id, string $username, string $senha)
    {
        $this->biblioteca = new BibliotecaDev($id);
        $this->profile_name = $username;

        $this->id = $id;
        $this->username = $username;
        $this->senha = $senha;
    }
    public function SetProfileName(string $publisher_name): void
    {
        $this->publisher_name = $publisher_name;
    }
    public function SetSaldo(float $valor): void
    {
        $this->saldo = $valor;
    }
    public function GetUserId(): string
    {
        return $this->id;
    }
    public function GetUsername(): string
    {
        return $this->username;
    }
    public function GetSenha(): string
    {
        return $this->senha;
    }
    public function GetPublisherName(): string
    {
        return $this->publisher_name;
    }
    public function GetSaldo(): float
    {
        return $this->saldo;
    }
    public function GetUserBiblioteca(): BibliotecaUsuario
    {
        return $this->biblioteca;
    }
    public function GetUserFriendList(): array
    {
        return $this->lista_amigos;
    }

    public function AdicionarAmigo(array $amigoInfo): void
    {
        $this->lista_amigos[] = $amigoInfo;
    }

    public function isInFriendList(string $amigo_nome): bool {
        foreach($this->lista_amigos as $amigo){
            if($amigo["friend_name"] == $amigo_nome){
                return true;
            }
        }
        
        return false;
    }
    public function __toString()
    {
        return "\n|USUARIO: $this->username          PUBLISHER: $this->publisher_name|";
    }
}

