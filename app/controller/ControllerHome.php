<?php

namespace App\Controller;

use App\View\ViewHome;
use App\Controller\ControllerPadrao;
use App\Controller\ControllerAdmin;

class ControllerHome extends ControllerPadrao
{

    protected function processPage()
    {
        $sTitle   = 'Shopping Ipm';
        $sContent = ViewHome::render([
            'products' => ViewHome::getHtmlProducts(ControllerAdmin::getProdutos())
        ]);

        return parent::getPage(
            $sTitle,
            $sContent
        );
    }
}
