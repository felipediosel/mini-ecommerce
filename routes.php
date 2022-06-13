<?php

function render($sPage)
{
    switch ($sPage) {
        case 'home':
            return (new App\Controller\ControllerHome)->render();
        case 'cart':
            return (new App\Controller\ControllerCart)->render();
        case 'login':
            return (new App\Controller\ControllerLogin)->render();
        case 'logout':
            return (new App\Controller\ControllerLogout)->render();
        case 'register':
            return (new App\Controller\ControllerRegister)->render();
        case 'admin':
            return (new App\Controller\ControllerAdmin)->render();
        case 'product':
            return (new App\Controller\ControllerProduct)->render();
    }

    return (new App\Controller\ControllerPageNotFound)->render();
}
