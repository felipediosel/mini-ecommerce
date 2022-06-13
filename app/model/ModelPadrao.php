<?php

namespace App\Model;

use App\Db\Connection;

abstract class ModelPadrao
{
    abstract function getTable();

    function getAll($aWhere = [])
    {
        $oConnection = Connection::get();

        $sWhere = count($aWhere) > 0 ? ' where  ' . implode(' and ', $aWhere) : '';

        $sSql = '
            select *
              from  ' . $this->getTable() . '
                    ' . $sWhere . '
             order by 1;
        ';

        $oQuery = pg_query($oConnection, $sSql);

        $aAll = [];

        for ($i = 0; $i < pg_numrows($oQuery); $i++) {
            $aAll[] = pg_fetch_array($oQuery, null, PGSQL_ASSOC);
        }

        return $aAll;
    }

    protected function insert($aValues)
    {
        $oConnection = Connection::get();

        $sSql = '
            insert 
              into ' . $this->getTable() . ' (' . implode(',', array_keys($aValues)) . ')
            values (' . implode(',', $aValues) . ');
        ';

        return pg_query($oConnection, $sSql);
    }

    protected function delete($aWhere)
    {
        $oConnection = Connection::get();

        $sSql = '
            delete 
              from ' . $this->getTable() . '
        ';

        if (count($aWhere) > 0) {
            $sSql .= ' where ' . implode(' and ', $aWhere);
        }

        return pg_query($oConnection, $sSql);
    }

    protected function update($aValues, $aWhere)
    {
        $oConnection = Connection::get();

        $aSet = [];

        foreach ($aValues as $sKey => $sValue) {
            if(!empty($sValue)){
                $aSet[] = $sKey . ' = ' . $this->getBdValue($sValue);
            }
        }

        if (count($aSet) > 0) {
            $sSql = '
                update ' . $this->getTable() . '
                   set ' . implode(', ', $aSet) . '
            ';

            if (count($aWhere) > 0) {
                $sSql .= ' where ' . implode(' and ', $aWhere);
            }

            return pg_query($oConnection, $sSql);
        }

        return true;
    }

    protected function getBdValue($xValue)
    {
        if (!empty($xValue) || !is_null($xValue)) {
            if (is_string($xValue)) {
                return '\'' . pg_escape_string($xValue) . '\'';
            }

            return $xValue;
        }

        return 'NULL';
    }
}
