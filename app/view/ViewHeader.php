<?php

namespace App\View;

use App\View\ViewPadrao;

class ViewHeader extends ViewPadrao
{

    static function render(array $aVars = [])
    {

        $aVars['menuCart']          = '';
        $aVars['menuAdmin']         = '';
        $aVars['menuWelcome']       = '';
        $aVars['menuWelcomeLogin']  = '';
        $aVars['menuWelcomeLogout'] = '';

        $sStyleShowMenu = self::getStyleShowMenu();
        $sStyleHideMenu = self::getStyleHideMenu();

        if (isset($aVars['isAdmin'])) {
            $aVars['menuAdmin'] = (
                $aVars['isAdmin'] ? $sStyleShowMenu : 
                                    $sStyleHideMenu
            );
        }

        if (isset($aVars['isLogged'])) {
            $aVars['menuWelcomeLogin'] = (
                $aVars['isLogged'] ? $sStyleHideMenu : 
                                     $sStyleShowMenu
            );

            $aVars['menuWelcomeLogout'] = (
                $aVars['isLogged'] ? $sStyleShowMenu : 
                                     $sStyleHideMenu
            );
        }

        return parent::render($aVars);
    }

    private static function getStyleShowMenu()
    {
        return 'style="display: block"';
    }

    private static function getStyleHideMenu()
    {
        return 'style="display: none"';
    }
}
