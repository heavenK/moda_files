<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaDoor extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_door';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'Id';
	
	
	function savetar($data){
			

			return $this->create($data);

	}
	
	
	
	function updatetar($Dat){
			
			//var_dump($Dat['user_id']);
			$this->DeleteImg($Dat['user_id']);	
			
			//$this->removeAll();	
			$this->removeByConditions("user_id = ".$Dat['user_id']."");	
			return $this->create($Dat);

	}
	function IndexTest(){
			return $this->find("user_id = -1");

	}
	function test(){
			return $this->find("status = 1");

	}
	function getatest($user_id){
			return $this->find("user_id = ".$user_id."");

	}
	function DeleteImg($user_id)
	{
			$data = $this->getatest($user_id);
			if($data['img_1'])
				unlink($data['img_1']);
			if($data['img_2'])
				unlink($data['img_2']);
			if($data['img_3'])
				unlink($data['img_3']);
			if($data['img_4'])
				unlink($data['img_4']);
			if($data['img_5'])
				unlink($data['img_5']);
	}
	
	

}
