<?php

namespace Bcca2\Steam\Model;

use Bcca2\Steam\Controller\BibliotecaDev;

class UserDev
{
    private string $id;
    private string $username;
    private string $publisher_name;
    private string $senha;
    private BibliotecaDev $biblioteca;

    function __construct(string $id, string $username, string $senha, string $publisher_name = "")
    {
        $this->biblioteca = new BibliotecaDev($id);

        $this->id = $id;
        $this->username = $username;
        $this->senha = $senha;
        $this->publisher_name = $publisher_name;
    }
    public function SetPublisherName(string $publisher_name): void
    {
        $this->publisher_name = $publisher_name;
    }

    public function SetProfileName(string $publisher_name): void
    {
        $this->SetPublisherName($publisher_name);
    }

    public function GetUserId(): string
    {
        return $this->id;
    }
    public function SetUsername(string $username): void
    {
        $this->username = $username;
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

    public function GetBibliotecaDev(): BibliotecaDev
    {
        return $this->biblioteca;
    }
    public function __toString()
    {
        return "\n|USUARIO: $this->username          PUBLISHER: $this->publisher_name|";
    }
}

