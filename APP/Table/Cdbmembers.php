<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_Cdbmembers extends FLEA_Db_TableDataGateway
{

    var $tableName = 'cdb_members';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'uid';
	

	function _getUserByUsername($username){
		return $this->find("username='".$username."'");
	}



}
