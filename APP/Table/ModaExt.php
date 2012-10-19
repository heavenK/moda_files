<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaExt extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_ext';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'user_id';
	
	function beingcheck($data)
	{
		if(!$data['user_id'])
			return false;
		else
			return $this->find("user_id=".$data['user_id']);
	}
	function updateext($data)
	{
			if($this->beingcheck($data))
				$suc = $this->save($data);
			else
				$suc = $this->create($data);
			return $suc;	
	}
	
	
	
	
	
	
	
}
