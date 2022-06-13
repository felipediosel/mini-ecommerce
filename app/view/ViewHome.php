<?php

namespace App\View;

use App\View\ViewPadrao;

class ViewHome extends ViewPadrao
{

    static function getHtmlProducts(array $aProdutos = [])
    {
        $sHtml = '';

        if (count($aProdutos) > 0) {
            foreach ($aProdutos as $iKey => $aProduto) {
                $sHtml .= '<div class="col">';

                $sId     = $aProduto['proid'];
                $sNome   = $aProduto['proname'];
                $sValor  = $aProduto['provalue'];
                $sImagem = file_exists($aProduto['proimageurl']) ? $aProduto['proimageurl'] : 'http://via.placeholder.com/250x250';
                $sInfo   = !empty($aProduto['proinfo']) ? $aProduto['proinfo'] : 'Este produto não contêm informações adicionais.';

                $sHtml .= '
                    <div class="card bg-dark bg-gradient">
                        <img src="' . $sImagem . '" alt="' . $sNome . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $sNome . '</h5>
                            <p class="card-text moneyFormat">
                                ' . $sValor . '
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index.php?pg=cart&act=insert&proid=' . $sId . '" class="btn btn-primary">
                                    <span class="p-1"><i class="fa-solid fa-cart-plus fa-lg"></i></span>
                                    Adicionar
                                </a>
                                <button type="button" class="btn btn-secondary" data-bs-trigger="focus" data-bs-toggle="popover" title="' . $sNome . '" data-bs-content="' . $sInfo . '"><span class="p-2"><i class="fa-solid fa-circle-info fa-lg"></i></span></button>
                            </div>
                        </div>
                    </div>
                ';

                $sHtml .= '</div>';
            }
        }

        return $sHtml;
    }
}
