<?php

namespace App\Controller;

use App\Controller\ControllerPadrao,
    App\Controller\ControllerAdmin;

use App\View\ViewProduct;

use App\Model\ModelProduct,
    App\Model\ModelCart;

class ControllerProduct extends ControllerPadrao
{

    private $fileUrl;

    function processPage()
    {
        $sTitle   = 'Produto';
        $sContent = ViewProduct::render(
            $this->getContentVars()
        );

        return parent::getPage(
            $sTitle,
            $sContent
        );
    }

    protected function getContentVars()
    {
        $xId = $_GET['id'] ??= '';

        if ($xModelProduct = $this->getModelProductFromId($xId)) {
            return [
                'id'       => $xModelProduct->getId(),
                'name'     => $xModelProduct->getName(),
                'price'    => $xModelProduct->getValue(),
                'imageurl' => $xModelProduct->getImageUrl(),
                'info'     => $xModelProduct->getInfo(),
                'act'      => 'update&id=' . $xModelProduct->getId(),
                'required' => ''
            ];
        }

        return [
            'id'       => '',
            'name'     => '',
            'price'    => '0.00',
            'imageurl' => '',
            'info'     => '',
            'act'      => 'insert',
            'required' => 'required'
        ];
    }

    protected function getModelProductFromId($xId)
    {
        if (!empty($xId) && !is_null($xId)) {
            $oModelProduct = new ModelProduct;
            $oModelProduct->setId($xId);

            $oModelProduct->persistProduct();

            return $oModelProduct;
        }

        return false;
    }

    protected function processInsert()
    {
        if ($this->processInsertFile($this)) {
            $this->processInsertProduct();
        }

        return $this->processPage();
    }

    protected function processInsertProduct()
    {
        $oModelProduct = $this->getModelProductFromPost();

        if (!empty($this->fileUrl)) {
            $oModelProduct->setImageUrl($this->fileUrl);
        }

        if ($oModelProduct->insertProduct()) {
            return $this->showMessageSucess('Produto inserido com sucesso!');
        }

        return $this->showMessageError('Ocorreu um erro ao tentar inserir o produto.');
    }

    protected function getModelProductFromPost()
    {
        $sId    = $_POST['id'] ??= null;
        $sName  = $_POST['name'] ??= '';
        $sValue = $_POST['value'] ??= 0;
        $sInfo  = $_POST['info'] ??= '';

        $oModelProduct = new ModelProduct;
        $oModelProduct->setId($sId);
        $oModelProduct->setName($sName);
        $oModelProduct->setValue((float) $sValue);
        $oModelProduct->setImageUrl($this->fileUrl);
        $oModelProduct->setInfo($sInfo);

        return $oModelProduct;
    }

    protected function processInsertFile($oController)
    {
        if (isset($_FILES['image'])) {
            $aFile = $_FILES['image'];

            if ($this->validFile($aFile, $oController)) {
                $sPath          = __DIR__ . '/../../temp/';
                $sFileTempName  = $aFile['tmp_name'];
                $sFileNewName   = uniqid();
                $sFileExtension = $this->getFileExtension($aFile);

                $this->fileUrl = 'temp/' . $sFileNewName . '.' . $sFileExtension;

                return move_uploaded_file($sFileTempName, $sPath . $sFileNewName . '.' . $sFileExtension);
            }
        }

        return false;
    }

    protected function validFile($aFile, $oController)
    {
        if ($aFile['error'] == UPLOAD_ERR_NO_FILE) {
            return false;
        }

        if ($aFile['error']) {
            return $oController->showMessageError('Falha ao enviar arquivo.');
        }

        if ($aFile['size'] > 2097152) {
            return $oController->showMessageError('Arquivo muito grande! Máximo: 2MB.');
        }

        if (!in_array($this->getFileExtension($aFile), ['jpeg', 'jpg', 'png', 'webp'])) {
            return $oController->showMessageError('Tipo de arquivo não aceito.');
        }

        return true;
    }

    protected function getFileExtension($aFile)
    {
        return strtolower(pathInfo($aFile['name'], PATHINFO_EXTENSION));
    }

    protected function processDelete()
    {
        $oControllerAdmin = new ControllerAdmin;

        $xId = $_GET['id'] ??= null;

        if ($oModelProduct = $this->getModelProductFromId($xId)) {
            if ($this->processDeleteProduct($oControllerAdmin, $oModelProduct)) {
                $this->processDeleteFile($oModelProduct);
            }
        }

        return $oControllerAdmin->processPage();
    }

    protected function processDeleteProduct(ControllerAdmin $oControllerAdmin, ModelProduct $oModelProduct)
    {
        $xId = $oModelProduct->getId();

        if($this->isExistsProductCart($oModelProduct)){
            return $oControllerAdmin->showMessageError('Produto: ' . $xId . ', relacionado ao carrinho.');
        }

        if ($oModelProduct->deleteProduct()) { 
            return $oControllerAdmin->showMessageSucess('Produto ' . $xId . ' excluído com sucesso!');
        }

        return $oControllerAdmin->showMessageError('Ocorreu um erro ao excluir o produto: ' . $xId . '.');
    }

    protected function isExistsProductCart(ModelProduct $oModelProduct){
        $oModelCart = new ModelCart;
        $oModelCart->setProduct($oModelProduct);

        return $oModelCart->isExistsProduct();
    }

    protected function processDeleteFile(ModelProduct $oModelProduct)
    {
        $xFileUrl = $oModelProduct->getImageUrl();

        if (!empty($xFileUrl)) {
            $sFileUrl = __DIR__ . '/../../' . $xFileUrl;

            if (file_exists($sFileUrl)) {
                unlink($sFileUrl);

                return true;
            }
        }

        return false;
    }

    protected function processUpdate()
    {
        $oControllerAdmin = new ControllerAdmin;

        if ($this->processInsertFile($oControllerAdmin)) {
            $xId = $_GET['id'] ??= null;

            if ($oModelProduct = $this->getModelProductFromId($xId)) {
                $this->processDeleteFile($oModelProduct);
            }
        }

        $this->processUpdateProduct($oControllerAdmin);

        return $oControllerAdmin->processPage();
    }

    protected function processUpdateProduct(ControllerAdmin $oControllerAdmin)
    {

        $oModelProduct = $this->getModelProductFromPost();

        $aValues = $oModelProduct->getColumnsChange($oModelProduct->getColumnsValues());

        $xId = $oModelProduct->getId();

        if ($oModelProduct->updateProduct($aValues)) {
            return $oControllerAdmin->showMessageSucess('Produto ' . $xId . ' alterado com sucesso!');
        }
    }
}
