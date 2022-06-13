<?php

namespace App\Controller;

use App\Controller\ControllerPadrao,
    App\Controller\ControllerLogin,
    App\Client\Session;

class ControllerLogout extends ControllerPadrao
{

    private $ControllerLogin;

    function getControllerLogin()
    {
        if (!isset($this->ControllerLogin)) {
            $this->ControllerLogin = new ControllerLogin;
        }

        return $this->ControllerLogin;
    }

    function processPage()
    {
        return $this->getControllerLogin()->processPage();
    }

    protected function processInsert()
    {
        $oControllerLogin = $this->getControllerLogin();

        if ((new Session)->logout()) {
            $oControllerLogin->showMessageSucess('Usuário desconectado.');
        } else {
            $oControllerLogin->showMessageError('Sessão não encontrada.');
        }

        return $oControllerLogin->processPage();
    }
}
