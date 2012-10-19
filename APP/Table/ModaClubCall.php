<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaClubCall extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_clubcall';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'Id';
	
	

	function savetar($data)
	{
		return $this->create($data);
	}
	
	function getAdminCallByClubId($club_id)
	{
		return $this->find("club_id = ".$club_id." and power = 1");
	}
	
	function getCallByClubId($club_id)
	{
		return $this->findAll("club_id = '".$club_id."'");
	}
	
	function getCallById($Id)
	{
		return $this->find("Id = '".$Id."'");
	}
	
	function deleteById($Id)
	{
		$this->removeByPkv($Id);
	}
	
	/*根据主席ID删除内容
	*/
	function deleteByCharId($Id)
	{
		$this->removeByConditions("chair_id = ".$Id."");
	}
	
	
	function updatetar($data)
	{
		return $this->updateByConditions("Id=".$data['Id']."",$data);
	}
	
	
///////////新后台

	function getClubCallCountByChairid($chair_id){
		
		return $this->findCount("chair_id= ".$chair_id."");
	}
	
}
