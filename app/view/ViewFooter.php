<?php

namespace App\View;

use App\View\ViewPadrao;

class ViewFooter extends ViewPadrao
{
    static function render(array $aVars = [])
    {

        $aVars['message'] = '';

        if (isset($aVars['messageSucess'])) {
            $aVars['message'] = self::getHtmlMessageSucess($aVars['messageSucess']);
        }

        if (isset($aVars['messageError'])) {
            $aVars['message'] = self::getHtmlMessageError($aVars['messageError']);
        }

        return parent::render($aVars);
    }

    private static function getHtmlMessageSucess($sMessage)
    {
        return self::getHtmlMessage($sMessage, 'bg-success');
    }

    private static function getHtmlMessageError($sMessage)
    {
        return self::getHtmlMessage($sMessage, 'bg-danger');
    }

    private static function getHtmlMessage($sMessage, $sClass)
    {
        return '
            <div id="liveToast" class="toast align-items-center text-white ' . $sClass . ' border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ' . $sMessage . '
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <script>
                (new bootstrap.Toast(document.getElementById("liveToast"))).show();
            </script>
        ';
    }
}
