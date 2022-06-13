<?php

namespace App\View;

use App\View\ViewPadrao;

class ViewAdmin extends ViewPadrao
{

    static function getHtmlProducts(array $aProdutos = [])
    {
        $sHtml = '';

        if (count($aProdutos) > 0) {
            foreach ($aProdutos as $iKey => $aProduto) {
                $sHtml .= '<tr>';

                $sId     = $aProduto['proid'];
                $sNome   = $aProduto['proname'];
                $sValor  = $aProduto['provalue'];
                $sImagem = $aProduto['proimageurl'];

                $sHtml .= '
                    <th scope="row" height="62.5px">' . $sId . '</th>
                    <td>' . $sNome . '</td>
                    <td class="moneyFormat">' . $sValor . '</td>
                    <td><a href="index.php?pg=product&id=' . $sId . '"><i class="fa-solid fa-pen-to-square fa-lg text-warning"></i></a></td>
                    <td><a href="index.php?pg=product&act=delete&id=' . $sId . '"><i class="fa-solid fa-trash-can fa-lg text-danger"></i></a></td>
                ';

                $sHtml .= '</tr>';
            }
        }
        else {
            $sHtml .= '
                <td>
                    <i class="fa-solid fa-basket-shopping fa-xl p-2"></i>
                    Nenhum produto cadastrado
                </td>
            ';
        }

        return $sHtml;
    }
}
