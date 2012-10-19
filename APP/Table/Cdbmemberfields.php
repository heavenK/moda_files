<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_Cdbmemberfields extends FLEA_Db_TableDataGateway
{

    var $tableName = 'cdb_memberfields';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'uid';
	

	function _getUserByNickname($nickname){
		return $this->find("nickname='".$nickname."'");
	}


	function _getUserByUID($uid){
		return $this->find("uid='".$uid."'");
	}


}
