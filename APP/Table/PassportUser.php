<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_PassportUser extends FLEA_Db_TableDataGateway
{

    var $tableName = 'passport_user';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'uid';
	
	function getUserByUsername($username)
	{
		return $this->find("username = '".$username."'");
	}

	
	
	
}
