<?php

namespace App\View;

use App\View\ViewPadrao;

class ViewCart extends ViewPadrao
{
    static function getHtmlProducts(array $aProducts = [])
    {
        $sHtml = '';

        if (count($aProducts) > 0) {
            foreach ($aProducts as $iKey => $oModelProduct) {
                $sHtml .= '<tr>';

                $sId       = $oModelProduct->getId();
                $sNome     = $oModelProduct->getName();
                $sValor    = $oModelProduct->getValue();
                $sImageUrl = $oModelProduct->getImageUrl();

                $sHtml .= '
                    <td style="text-align: start;"><img src="' . $sImageUrl . '" width="125px" height="125px"></td>
                    <td>' . $sNome . '</td>
                    <td name="amount">
                        <a>
                            <i class="fa-solid fa-minus"></i>
                        </a>
                        <span class="">
                        1
                        </span>
                        <a>
                            <i class="fa-solid fa-plus"></i>
                        </a> 
                    </td>
                    <td class="moneyFormat">' . $sValor . '</td>
                    <td><a href="index.php?pg=cart&act=delete&proid=' . $sId . '"><i class="fa-solid fa-trash-can fa-lg text-danger"></i></a></td>
                ';

                $sHtml .= '</tr>';
            }
        }
        else {
            $sHtml .= '
                <td>
                    <i class="fa-solid fa-cart-arrow-down fa-xl p-2"></i>
                    Carrinho vazio
                </td>
            ';
        }

        return $sHtml;
    }
}
