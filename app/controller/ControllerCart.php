<?php

namespace App\Controller;

use App\Controller\ControllerPadrao,
    App\Controller\ControllerLogin,
    App\Controller\ControllerHome;

use App\View\ViewCart;

use App\Model\ModelCart,
    App\Model\ModelProduct;

use App\Client\Session;

class ControllerCart extends ControllerPadrao
{

    function render()
    {
        if (!(new Session)->isLogged()) {
            $oControllerLogin = new ControllerLogin;

            $oControllerLogin->showMessageError('Faça login para continuar.');

            return $oControllerLogin->processPage();
        }

        return parent::render();
    }

    function processPage()
    {
        $sTitle   = 'Carrinho de Compras';
        $sContent = ViewCart::render([
            'products' => ViewCart::getHtmlProducts($this->getUserProducts())
        ]);

        return parent::getPage(
            $sTitle,
            $sContent
        );
    }

    function getUserProducts()
    {
        $oModelCart = new ModelCart;
        $oModelCart->setUser((new Session)->getModelUser());

        return $oModelCart->getUserModelProducts();
    }

    function processInsert()
    {
        $xProductId = $_GET['proid'] ??= null;

        $oControllerHome = new ControllerHome;

        if ($xProductId) {
            $oModelProduct = new ModelProduct;
            $oModelProduct->setId($xProductId);

            if ($oModelProduct->getProduct()) {
                $oModelUser = (new Session)->getModelUser();

                $oModelCart = new ModelCart;
                $oModelCart->setUser($oModelUser);
                $oModelCart->setProduct($oModelProduct);

                if (!$oModelCart->getCart()) {
                    if ($oModelCart->insertCart()) {
                        $this->showMessageSucess('Produto adicionado ao carrinho.');

                        return $this->processPage();
                    } else {
                        $oControllerHome->showMessageError('Não foi possível adicionar o produto no carrinho.');
                    }
                } else {
                    $oControllerHome->showMessageError('Produto já está no carrinho. <a href="index.php?pg=cart" class="link-light">Ver carrinho</a>');
                }
            } else {
                $oControllerHome->showMessageError('Produto ' . $xProductId . ' não existe.');
            }
        }

        return $oControllerHome->processPage();
    }

    function processDelete()
    {
        $xProductId = $_GET['proid'] ??= null;

        if ($xProductId) {
            $oModelProduct = new ModelProduct;
            $oModelProduct->setId($xProductId);

            if ($oModelProduct->getProduct()) {
                $oModelUser = (new Session)->getModelUser();

                $oModelCart = new ModelCart;
                $oModelCart->setUser($oModelUser);
                $oModelCart->setProduct($oModelProduct);

                if ($oModelCart->deleteCart()) {
                    $this->showMessageSucess('Produto excluído do carrinho.');
                } else {
                    $this->showMessageError('Não foi possível excluir o produto do carrinho.');
                }
            }
        }

        return $this->processPage();
    }
}
