<?php

namespace App\Client;

use App\Model\ModelUser,
    App\Model\ModelCart;

class Session
{
    function __construct()
    {
        $this->init();
    }

    private function init()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    function login($sIdUser)
    {
        if (!$this->isLogged()) {
            $_SESSION['userid'] = $sIdUser;

            return true;
        }

        return false;
    }

    function logout()
    {
        if ($this->isLogged()) {
            unset($_SESSION['userid']);

            return true;
        }

        return false;
    }

    function isLogged()
    {
        return isset($_SESSION['userid']) && !empty($_SESSION['userid']);
    }

    function getUserId()
    {
        if ($this->isLogged()) {
            return $_SESSION['userid'];
        }

        return false;
    }

    function getModelUser()
    {
        if ($xUserId = $this->getUserId()) {
            $oModelUser = new ModelUser;
            $oModelUser->setId($xUserId);

            $oModelUser->persistUser();

            return $oModelUser;
        }

        return false;
    }

    function isAdmin()
    {
        if ($xModelUser = $this->getModelUser()) {
            return $xModelUser->isAdmin();
        }

        return false;
    }

    function getWelcomeMessage()
    {
        if ($xModelUser = $this->getModelUser()) {
            return 'Olá ' . $xModelUser->getLogin();
        }

        return 'Olá Visitante';
    }

    function getCartCount()
    {
        if ($xModelUser = $this->getModelUser()) {
            $oModelCart = new ModelCart;
            $oModelCart->setUser($xModelUser);

            return $oModelCart->getCountUserProducts();
        }

        return 0;
    }
}
