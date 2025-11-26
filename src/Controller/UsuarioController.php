<?php

namespace Bcca2\Steam\Controller;

use Bcca2\Steam\Model\Usuario;
use Bcca2\Steam\Controller\LeEscreveCsv;

class UsuarioController extends LeEscreveCsv
{
    private Usuario $currentUser;
    private string $pathInventario;

    public function __construct(Usuario $currentUser)
    {
        $this->path = dirname(__DIR__) . '\components\usersData.csv';

        $this->pathInventario = dirname(__DIR__) . '\components\inventarioUsuarios.csv';

        $this->currentUser = $currentUser;

        $this->AdicionarNovoUserAoCsv();

        $this->PreencherObj();

        $this->PreencherFriendList();

        $this->PreencherCardList();
    }

    protected function PreencherObj(): void
    {
        $handleUsersData = fopen($this->path, "r");

        while (($user = fgetcsv($handleUsersData)) !== false) {
            $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);
            if ($this->currentUser->GetUserId() == $infoUsuario["id"]) {
                $this->currentUser->SetProfileName($infoUsuario["profile_name"]);
                $this->currentUser->SetSaldo($infoUsuario["saldo"]);
                break;
            }
        }
        fclose($handleUsersData);
    }

    public function GetCurrentUser(): Usuario
    {
        return $this->currentUser;
    }

    public function AdicionarNovoUserAoCsv(): void
    {
        if (!$this->IsInCsv($this->currentUser->GetUserId())) {
            $user = [$this->currentUser->GetUserId(), $this->currentUser->GetProfileName(), $this->currentUser->GetSaldo()];

            $this->UpdateCsv($user);
        }
    }

    public function PreencherFriendList()
    {
        if ($this->IsListaAtualizada($this->currentUser->GetUserFriendList())) {
            return;
        }

        $handleFriendsData = fopen(dirname(__DIR__) . '\components\usersFriends.csv', "r");

        while (($friend = fgetcsv($handleFriendsData)) !== false) {
            $friendId = $friend[0];

            if ($friendId == $this->currentUser->GetUserId()) {
                $friendinfo = array("id" => $friendId, "friend_name" => $friend[2]);
                $this->currentUser->AdicionarAmigo($friendinfo);
            }
        }

        fclose($handleFriendsData);
    }

    public function PreencherCardList()
    {
        if ($this->IsListaAtualizada($this->currentUser->getCartas())) {
            return;
        }

        $handleCardsData =  fopen($this->pathInventario, "r");

        while (($card = fgetcsv($handleCardsData)) !== false) {
            $cardInfo = array("id_carta" => $card[0], "id_jogo" => $card[1], "id_usuario" => $card[2], "nome_carta" => $card[3]);
            $this->currentUser->AdicionarCartas($cardInfo);
        }
    }

    public function AdicionarAmigoByName(string $friendName): void
    {
        if (!$this->IsInCsv($friendName)) {
            echo "\n!!!Usuario nao encontrado!!!\n";
            return;
        }

        if ($friendName == $this->currentUser->GetUsername()) {
            echo "\n!!!Nao e possivel adicionar a si mesmo como amigo!!!\n";
            return;
        }

        $path = dirname(__DIR__) . '\components\usersFriends.csv';

        if ($this->currentUser->isInFriendList($friendName)) {
            echo "\n!!!Amigo ja adicionado a sua lista de amigos!!!\n";
            return;
        }

        $friend = $this->getCsvRowByName($friendName);

        echo "\n!!!Usuario encontrado!!!\n";

        $friendInfo = ["id_usuario" => $this->currentUser->GetUserId(), "id_amigo" => $friend[0], "friend_name" => $friend[1]];

        echo "\n!!!Adicionando ", $friendInfo["friend_name"], " como amigo...\n";

        if ($this->UpdateCsv($friendInfo, $path)) {
            echo "\n!!!Amigo adicionado com sucesso!!!\n";
            $this->currentUser->AdicionarAmigo($friendInfo);
        } else {
            echo "\n!!!Erro ao adicionar este usuario!!!\n";
        }
    }

        public function DeletarAmigoByName(string $friendName): void
    {
        if (!$this->IsInCsv($friendName)) {
            echo "\n!!!Usuario nao encontrado!!!\n";
            return;
        }

        if ($friendName == $this->currentUser->GetUsername()) {
            echo "\n!!!Nao e possivel desfazer amizade com si mesmo!!!\n";
            return;
        }

        $path = dirname(__DIR__) . '\components\usersFriends.csv';

        if (!$this->currentUser->isInFriendList($friendName)) {
            echo "\n!!!Este usuário já não é mais seu amigo.....\n";
            return;
        }

        $handleRead = fopen($path, "r");
        //Precisa estar aqui para pular a primeira linha (header)
        fgetcsv($handleRead);

        $updatedCsv = array();

        while (($user = fgetcsv($handleRead)) !== false) {
            if ($user[2] != $friendName) {
                $infoAmigo = array($user[0], $user[1], $user[2]);
                array_push($updatedCsv, $infoAmigo);
            }
        }

        fclose($handleRead);

        $handleWrite = fopen($path, "w");

        $header = ["id_usuario", "id_amigo", "amigo_name"];
        fputcsv($handleWrite, array_values($header));

        foreach ($updatedCsv as $user) {
            fputcsv($handleWrite, array_values($user));
        }

        $this->currentUser->AdicionarAmigo($updatedCsv);

        $this->PreencherFriendList();

        echo "\n!!!Removendo ", $friendName, " como amigo...\n";
    }

    public function MudarProfileName(string $id, string $novoProfileName): void
    {
        if ($novoProfileName == null || strlen($novoProfileName) < 3 || strlen($novoProfileName) > 12) {
            echo "\n!!!Informacoes invalidas para atualizar seu Profile Name!!!\n";
        } else {
            $handleRead = fopen($this->path, "r");
            $newUserCsv = array();

            //Precisa estar aqui para pular a primeira linha (header)
            fgetcsv($handleRead);

            while (($user = fgetcsv($handleRead)) !== false) {
                $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);

                if ($infoUsuario["id"] == $id) {
                    $infoUsuario["profile_name"] = $novoProfileName;
                }

                $newUserCsv[] = $infoUsuario;
            }

            fclose($handleRead);

            $handleWrite = fopen($this->path, "w");

            $header = ['id', 'profile_name', 'saldo', 'id_biblioteca', 'id_lista_amigos', 'id_lista_cartas'];
            fputcsv($handleWrite, array_values($header));

            foreach ($newUserCsv as $user) {
                fputcsv($handleWrite, array_values($user));
            }

            echo "\n!!!Profile atualizado com sucesso!!!\n";

            $this->currentUser->SetProfileName($novoProfileName);

            fclose($handleWrite);
        }
    }

    public function AtualizarSaldo(string $id, float $valor): bool
    {
        if (!is_float($valor)) {
            echo "\n!!!Nao foi possivel atualizar seu Saldo\nValor passado invalido!!!\n";
            return false;
        } else {
            $path = $this->path;
            $handleRead = fopen($path, "r");
            $newUserCsv = array();
            $novoSaldo = 0;

            //Precisa estar aqui para pular a primeira linha (header)
            fgetcsv($handleRead);

            while (($user = fgetcsv($handleRead)) !== false) {
                $infoUsuario = array("id" => $user[0], "profile_name" => $user[1], "saldo" => $user[2]);

                if ($infoUsuario["id"] == $id) {
                    $novoSaldo = $infoUsuario["saldo"] += $valor;

                    $infoUsuario["saldo"] = $novoSaldo;
                }

                $newUserCsv[] = $infoUsuario;
            }

            fclose($handleRead);

            $handleWrite = fopen($path, "w");

            $header = ['id', 'profile_name', 'saldo', 'id_biblioteca', 'id_lista_amigos', 'id_lista_cartas'];
            fputcsv($handleWrite, array_values($header));

            foreach ($newUserCsv as $user) {
                fputcsv($handleWrite, array_values($user));
            }

            echo "\n!!!Saldo atualizado com sucesso!!!\n";

            $this->currentUser->SetSaldo($novoSaldo);

            fclose($handleWrite);

            return true;
        }
    }

    public function adicionarCartas(array $cartas)
    {
        $temp = $this->currentUser->getCartas();

        array_push($temp, $cartas);

        $this->currentUser->setCartas($temp);

        foreach ($cartas as $carta) {
            $novaCarta = ["id" => $carta->getId(), "idJogo" => $carta->getIdJogo(), "idUsuario" => $this->currentUser->GetUserId(), "nome" => $carta->getNome()];

            $this->UpdateCsv($novaCarta, $this->pathInventario);
        }
    }

    public function removerCarta($cartaId)
    {
        $elementoRemover = [$cartaId];
        $tempUserCartas = array_diff($this->currentUser->getCartas(), $elementoRemover);
        $this->currentUser->setCartas($tempUserCartas);

        $handleCartaData = fopen($this->pathInventario, "r");

        $tempListaCartas = [];

        while (($carta = fgetcsv($handleCartaData)) !== false) {
            if ($this->currentUser->getUserId() == $carta[1] && $carta[0] != $cartaId) {
                $infoCarta = array("id" => $carta[0], "idJogo" => $carta[1]);
                array_push($this->tempListaCartas, $infoCarta);
            }
        }
        fclose($handleCartaData);

        $handleCartaData = fopen($this->pathInventario, 'w');

        foreach ($tempListaCartas as $carta) {
            fputcsv($handleCartaData, array_values($carta));
        }
    }
}
