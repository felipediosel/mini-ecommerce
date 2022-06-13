<?php

namespace App\Controller;

use App\Controller\ControllerPadrao,
    App\Controller\ControllerHome,
    App\View\ViewLogin,
    App\Model\ModelUser,
    App\Client\Session;

class ControllerLogin extends ControllerPadrao
{

    function processPage()
    {
        $sTitle   = 'Login';
        $sContent = ViewLogin::render([]);

        return parent::getPage(
            $sTitle,
            $sContent
        );
    }

    protected function processInsert()
    {
        $xLogin    = $_POST['login'] ??= '';
        $sPassword = $_POST['password'] ??= '';

        if (!empty($xLogin)) {
            $sLogin = '@' . $xLogin;

            $oModelUser = new ModelUser;
            $oModelUser->setLogin($sLogin);

            if ($oModelUser->persistUser()) {
                if (password_verify($sPassword, $oModelUser->getPassword())) {
                    return $this->processLogin($oModelUser);
                } else {
                    $this->showMessageError('Usuário ou senha incorretos.');
                }
            } else {
                $this->showMessageError('Usuário não encontrado.');
            }
        }

        return $this->processPage();
    }

    function processLogin(ModelUser $oModelUser)
    {
        $oControllerHome = new ControllerHome;
        $oSession        = new Session;

        if ($oSession->login($oModelUser->getId())) {
            $oControllerHome->showMessageSucess('Olá ' . $oModelUser->getLogin() . '. Seja bem vindo!');
        } else {
            $oModelUser = $oSession->getModelUser();

            $oControllerHome->showMessageError('Usuário ' . $oModelUser->getLogin() . ' já logado. <a href="index.php?pg=logout&act=insert" class="link-light">Desconectar</a>');
        }

        return $oControllerHome->processPage();
    }
}
