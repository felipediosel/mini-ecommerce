<?php

namespace App\Controller;

use App\Controller\ControllerPadrao,
    App\Controller\ControllerLogin;

use App\View\ViewRegister;
use App\Model\ModelUser;

class ControllerRegister extends ControllerPadrao
{

    function processPage()
    {
        $sTitle   = 'Registrar Usuário';
        $sContent = ViewRegister::render([]);

        return parent::getPage(
            $sTitle,
            $sContent
        );
    }

    protected function processInsert()
    {
        return $this->processInsertUser();
    }

    protected function processInsertUser()
    {
        $xLogin    = $_POST['login'] ??= '';
        $xPassword = $_POST['password'] ??= '';
        $xAdmin    = $_POST['admin'] ??= 0;

        $sLogin    = !empty($xLogin) ? '@' . $xLogin : '';
        $sPassword = password_hash($xPassword, PASSWORD_DEFAULT);
        $bAdmin    = $xAdmin == 'on' ? 1 : 0;

        $oModelUser = new ModelUser;
        $oModelUser->setLogin($sLogin);
        $oModelUser->setPassword($sPassword);
        $oModelUser->setAdmin($bAdmin);

        $oControllerLogin = new ControllerLogin;

        if (!$oModelUser->isLoginExists()) {
            if ($oModelUser->insertUser()) {
                $oModelUser->persistUser();

                return $oControllerLogin->processLogin($oModelUser);
            } else {
                $oControllerLogin->showMessageError('Ocorreu um erro ao tentar registrar o usuário.');
            }
        } else {
            $oControllerLogin->showMessageError('Usuário já existe.');
        }

        return $oControllerLogin->processPage();
    }
}
