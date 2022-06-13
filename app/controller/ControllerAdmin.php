<?php

namespace App\Controller;

use App\Controller\ControllerPadrao,
    App\Controller\ControllerHome;

use App\View\ViewAdmin;
use App\Model\ModelProduct;
use App\Client\Session;

class ControllerAdmin extends ControllerPadrao
{

    function render()
    {
        if (!(new Session)->isAdmin()) {
            $oControllerHome = new ControllerHome;

            $oControllerHome->showMessageError('Usuário sem privilégio de acesso.');

            return $oControllerHome->processPage();
        }

        return parent::render();
    }

    function processPage()
    {
        $sTitle   = 'Administrador';
        $sContent = ViewAdmin::render([
            'products' => ViewAdmin::getHtmlProducts(self::getProdutos())
        ]);

        return parent::getPage(
            $sTitle,
            $sContent
        );
    }

    static function getProdutos()
    {
        return (new ModelProduct())->getAll();

        return [
            0 => [
                'id'     => 1,
                'nome'   => 'iPhone 13 Pro',
                'valor'  => '9.666',
                'imagem' => 'http://localhost/mini-e-com/temp/iphone13pro.jpg',
                'info'   => 'Novo sistema de câmera dramaticamente mais poderoso. Tela responsiva que surpreende a cada toque. O chip de smartphone mais rápido do mundo. Design com resistência fora de série. E um salto imenso na duração da bateria.'
            ],
            1 => [
                'id'     => 2,
                'nome'   => 'MacBook Pro',
                'valor'  => '16.645',
                'imagem' => 'http://localhost/mini-e-com/temp/macbookpro.jpg'
            ],
            2 => [
                'id'     => 3,
                'nome'   => 'iPad Pro',
                'valor'  => '10.391',
                'imagem' => 'http://localhost/mini-e-com/temp/ipadpro.jpg'
            ],
            3 => [
                'id'     => 4,
                'nome'   => 'Apple Watch Series 7',
                'valor'  => '5.099',
                'imagem' => 'http://localhost/mini-e-com/temp/applewatch.webp'
            ],
            4 => [
                'id'     => 5,
                'nome'   => 'Apple Watch Series 7',
                'valor'  => '5.099',
                'imagem' => 'http://via.placeholder.com/250x250'
            ],
            5 => [
                'id'     => 6,
                'nome'   => 'Apple Watch Series 7',
                'valor'  => '5.099',
                'imagem' => 'http://via.placeholder.com/250x250'
            ],
            6 => [
                'id'     => 7,
                'nome'   => 'Apple Watch Series 7',
                'valor'  => '5.099',
                'imagem' => 'http://via.placeholder.com/250x250'
            ],
            7 => [
                'id'     => 8,
                'nome'   => 'Apple Watch Series 7',
                'valor'  => '5.099',
                'imagem' => 'http://via.placeholder.com/250x250'
            ]
        ];
    }
}
