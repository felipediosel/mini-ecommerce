<?php

namespace App\Model;

use App\Model\ModelPadrao;

class ModelUser extends ModelPadrao
{
    private $id;
    private $login;
    private $password;
    private $admin;

    function getId()
    {
        return $this->id;
    }

    function setId($sId)
    {
        $this->id = $sId;
    }

    function getLogin()
    {
        return $this->login;
    }

    function setLogin($sLogin)
    {
        $this->login = $sLogin;
    }

    function getPassword()
    {
        return $this->password;
    }

    function setPassword($sPassword)
    {
        $this->password = $sPassword;
    }

    function getAdmin()
    {
        return $this->admin;
    }

    function setAdmin($sAdmin)
    {
        $this->admin = $sAdmin;
    }

    function getInfo()
    {
        return $this->info;
    }

    function setInfo($sInfo)
    {
        $this->info = $sInfo;
    }

    function getTable()
    {
        return 'tbuser';
    }

    function insertUser()
    {
        return parent::insert([
            'uselogin'    => $this->getBdValue($this->getLogin()),
            'usepassword' => $this->getBdValue($this->getPassword()),
            'useadmin'    => $this->getBdValue($this->getAdmin())
        ]);
    }

    function deleteUser()
    {
        return parent::delete([
            'useid = ' . $this->getBdValue($this->getId())
        ]);
    }

    function getUser()
    {
        $aFilter = [];

        if (!empty($this->getId())) {
            $aFilter[] = 'useid = ' . $this->getBdValue($this->getId());
        }

        if (!empty($this->getLogin())) {
            $aFilter[] = 'uselogin = ' . $this->getBdValue($this->getLogin());
        }

        $aAll = parent::getAll($aFilter);

        if (count($aAll) > 0) {
            return array_shift($aAll);
        }

        return false;
    }

    function persistUser()
    {
        if ($aUser = $this->getUser()) {
            $this->setId($aUser['useid']);
            $this->setLogin($aUser['uselogin']);
            $this->setPassword($aUser['usepassword']);
            $this->setAdmin($aUser['useadmin']);

            return true;
        }

        return false;
    }

    function isLoginExists()
    {
        $aAll = parent::getAll([
            'uselogin = ' . $this->getBdValue($this->getLogin())
        ]);

        if (count($aAll) > 0) {
            return true;
        }

        return false;
    }

    function isAdmin()
    {
        return $this->getAdmin() == 1 ? true : false;
    }
}
