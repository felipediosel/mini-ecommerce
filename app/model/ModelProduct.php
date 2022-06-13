<?php

namespace App\Model;

use App\Model\ModelPadrao;

class ModelProduct extends ModelPadrao
{
    private $id;
    private $name;
    private $value;
    private $imageUrl;
    private $info;

    function getId()
    {
        return $this->id;
    }

    function setId($sId)
    {
        $this->id = $sId;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($sName)
    {
        $this->name = $sName;
    }

    function getValue()
    {
        return $this->value;
    }

    function setValue($sValue)
    {
        $this->value = $sValue;
    }

    function getImageUrl()
    {
        return $this->imageUrl;
    }

    function setImageUrl($sImageUrl)
    {
        $this->imageUrl = $sImageUrl;
    }

    function getInfo()
    {
        return $this->info;
    }

    function setInfo($sInfo)
    {
        $this->info = $sInfo;
    }

    function getTable()
    {
        return 'tbproduct';
    }

    function insertProduct()
    {
        return parent::insert([
            'proname'     => $this->getBdValue($this->getName()),
            'provalue'    => $this->getBdValue($this->getValue()),
            'proimageurl' => $this->getBdValue($this->getImageUrl()),
            'proinfo'     => $this->getBdValue($this->getInfo())
        ]);
    }

    function deleteProduct()
    {
        return parent::delete([
            'proid = ' . $this->getBdValue($this->getId())
        ]);
    }

    function getProduct()
    {
        $aAll = parent::getAll([
            'proid = ' . $this->getBdValue($this->getId())
        ]);

        if (count($aAll) > 0) {
            return array_shift($aAll);
        }

        return false;
    }

    function persistProduct()
    {
        if ($aProduct = $this->getProduct()) {
            $this->setName($aProduct['proname']);
            $this->setValue($aProduct['provalue']);
            $this->setImageUrl($aProduct['proimageurl']);
            $this->setInfo($aProduct['proinfo']);
        }
    }

    function getColumnsChange(array $aProductUpdate)
    {
        $aProduct = $this->getProduct();

        foreach ($aProductUpdate as $sKey => $sValue) {
            if ($aProductUpdate[$sKey] == $aProduct[$sKey]) {
                unset($aProductUpdate[$sKey]);
            }
        }

        return $aProductUpdate;
    }

    function getColumnsValues()
    {
        return [
            'proid'       => $this->getId(),
            'proname'     => $this->getName(),
            'provalue'    => $this->getValue(),
            'proimageurl' => $this->getImageUrl(),
            'proinfo'     => $this->getInfo()
        ];
    }

    function updateProduct($aValues)
    {
        return parent::update(
            $aValues,
            ['proid = ' . $this->getBdValue($this->getId())]
        );
    }
}
