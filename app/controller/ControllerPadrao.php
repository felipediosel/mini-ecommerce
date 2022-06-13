<?php

namespace App\Controller;

use App\View\ViewPage,
    App\View\ViewHeader,
    App\View\ViewFooter;

use App\Client\Session;

abstract class ControllerPadrao
{

    protected $headerVars = [];
    protected $footerVars = [];

    function render()
    {
        $sAction = $_GET['act'] ??= '';

        switch ($sAction) {
            case 'insert':
                return $this->processInsert();
            case 'update':
                return $this->processUpdate();
            case 'delete':
                return $this->processDelete();
            default:
                return $this->processPage();
        }
    }

    protected function processInsert()
    {
    }

    protected function processUpdate()
    {
    }

    protected function processDelete()
    {
    }

    protected function processPage()
    {
    }

    protected function getHeader($aVars = [])
    {
        return ViewHeader::render($aVars);
    }

    protected function getFooter($aVars = [])
    {
        return ViewFooter::render($aVars);
    }

    protected function getPage($sTitle, $sContent)
    {

        $oSession = new Session;

        $sHeader = $this->getHeader(
            array_merge(
                $this->headerVars,
                [
                    'welcomeMessage' => $oSession->getWelcomeMessage(),
                    'isLogged'       => $oSession->isLogged(),
                    'isAdmin'        => $oSession->isAdmin(),
                    'cartCount'      => $oSession->getCartCount()
                ]
            )
        );

        $sFooter = $this->getFooter($this->footerVars);

        return ViewPage::render([
            'title'   => $sTitle,
            'header'  => $sHeader,
            'content' => $sContent,
            'footer'  => $sFooter
        ]);
    }

    protected function showMessageSucess($sMessage)
    {
        $this->footerVars['messageSucess'] = $sMessage;

        return true;
    }

    protected function showMessageError($sMessage)
    {
        $this->footerVars['messageError'] = $sMessage;

        return false;
    }
}
