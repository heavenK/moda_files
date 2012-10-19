<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaFans extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_fans';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'uid';
	
	function updateByCon($uname,$fname,$data)
	{
		return $this->updateByConditions("uname='".$uname."' and fusername='".$fname."'", $data);
	}
	function fanscheck($uname,$fname)
	{
		return $this->find("uname='".$uname."' and fusername='".$fname."'");
	}
	function addfans($data){
		return $this->create($data);
	}
	function updatefans($uname,$fname,$data)
	{
			if($this->fanscheck($uname,$fname,$data))
			{
				$suc = $this->updateByCon($uname,$fname,$data);
				$ret = 0;
			}
			else
			{
				$suc = $this->addfans($data);
				$ret = 1;
			}
			return $ret;	
	}
	
	
	
	
}
