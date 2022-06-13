<?php

namespace App\Model;

use App\Model\ModelPadrao,
    App\Model\ModelUser,
    App\Model\ModelProduct;

class ModelCart extends ModelPadrao
{
    private $User;
    private $Product;

    function getUser()
    {
        if (!isset($this->User)) {
            $this->User = new ModelUser;
        }

        return $this->User;
    }

    function setUser(ModelUser $oModelUser)
    {
        $this->User = $oModelUser;
    }

    function getProduct()
    {
        if (!isset($this->Product)) {
            $this->Product = new ModelProduct;
        }

        return $this->Product;
    }

    function setProduct(ModelProduct $oModelProduct)
    {
        $this->Product = $oModelProduct;
    }

    function getTable()
    {
        return 'tbcart';
    }

    function insertCart()
    {
        return parent::insert([
            'useid' => $this->getBdValue($this->getUser()->getId()),
            'proid' => $this->getBdValue($this->getProduct()->getId())
        ]);
    }

    function deleteCart()
    {
        return parent::delete([
            'useid = ' . $this->getBdValue($this->getUser()->getId()),
            'proid = ' . $this->getBdValue($this->getProduct()->getId())
        ]);
    }

    function getCart()
    {
        $aAll = parent::getAll([
            'useid = ' . $this->getBdValue($this->getUser()->getId()),
            'proid = ' . $this->getBdValue($this->getProduct()->getId())
        ]);

        if (count($aAll) > 0) {
            return array_shift($aAll);
        }

        return false;
    }

    function persistCart()
    {
        if ($aCart = $this->getCart()) {
            $this->getUser()->setId($aCart['useid']);
            $this->getProduct()->setId($aCart['proid']);

            return true;
        }

        return false;
    }

    function getUserModelProducts()
    {
        $aUserCart = parent::getAll([
            'useid = ' . $this->getBdValue($this->getUser()->getId())
        ]);

        $aProducts = [];

        if (count($aUserCart) > 0) {
            foreach ($aUserCart as $aCart) {
                $xProductId = $aCart['proid'] ??= null;

                $oModelProduct = new ModelProduct;
                $oModelProduct->setId($xProductId);
                $oModelProduct->persistProduct();

                $aProducts[] = $oModelProduct;
            }
        }

        return $aProducts;
    }

    function getCountUserProducts()
    {
        $aUserCart = parent::getAll([
            'useid = ' . $this->getBdValue($this->getUser()->getId())
        ]);

        return count($aUserCart);
    }

    function isExistsProduct()
    {
        $aAll = parent::getAll([
            'proid = ' . $this->getBdValue($this->getProduct()->getId())
        ]);

        if (count($aAll) > 0) {
            return true;
        }

        return false;
    }
}
