<?php

namespace Bcca2\Steam\Model;

use Bcca2\Steam\Controller\BibliotecaUsuario;

class Usuario
{
    private string $id;
    private string $username;
    private string $senha;
    private string $profile_name;
    protected float $saldo = 0;
    private BibliotecaUsuario $biblioteca;
    private array $lista_amigos = [];
    //private Cartas $lista_cartas;

    function __construct(string $id, string $username, string $senha)
    {
        $this->biblioteca = new BibliotecaUsuario($id);
        $this->profile_name = $username;

        $this->id = $id;
        $this->username = $username;
        $this->senha = $senha;
    }
    public function SetProfileName(string $profile_name): void
    {
        $this->profile_name = $profile_name;
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
    public function GetProfileName(): string
    {
        return $this->profile_name;
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
    public function UpdateUserFriendList():void{
        $this->lista_amigos = [];
    }

    public function AdicionarAmigo(array $amigoInfo): void
    {
        $this->lista_amigos[] = $amigoInfo;
    }

    public function RemoverAmigo(array $updatedCsv): void {


    }

    public function isInFriendList(string $amigo_nome): bool
    {
        foreach ($this->lista_amigos as $amigo) {
            if ($amigo["friend_name"] == $amigo_nome) {
                return true;
            }
        }

        return false;
    }
    public function __toString()
    {
        return "\n|USUARIO: $this->profile_name          SALDO: $this->saldo R$|";
    }
    public function toStringAmigo()
    {
        echo "|\n| USERNAME: ", $this->profile_name, "\n";
    }
}
