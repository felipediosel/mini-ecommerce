<?php

namespace App\Controller;

use App\View\ViewPageNotFound;
use App\Controller\ControllerPadrao;

class ControllerPageNotFound extends ControllerPadrao
{

    function processPage()
    {
        $sTitle   = 'Página não Encontrada';
        $sContent = ViewPageNotFound::render([]);

        return parent::getPage(
            $sTitle,
            $sContent
        );
    }
}
