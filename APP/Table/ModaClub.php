<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaClub extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_club';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'club_id';
	
	function getDetailByUserId($user_id)
	{
		return $this->findAll("uid=".$user_id."");
	}

	function savetar($data)
	{
		return $this->create($data);
	}
	
	function getDetailByClubId($club_id)
	{
		return $this->find("club_id=".$club_id."");
	}
	
	function updatetar($data)
	{
		
		
		return $this->updateByConditions("club_id=".$data['club_id']."",$data);
	}
	
	function deleteByClubId($club_id)
	{
		/*$da = $this->getDetailByClubId($club_id);
		$da['call_num']+=-1;
		$this->updatetar($da);*/
		$this->removeByPkv($club_id);

	}
	
	/*根据ID删除贴吧
	*/
	function deleteByUid($uid)
	{
		$this->removeByConditions("uid = ".$uid."");
	}
	
	
	/*根据用户ID删除所有贴吧及贴吧内容
	*/
	function deleteTotalByUid($uid)
	{
	
		/*删除贴吧
		*/
		$this->deleteByUid($uid);
		
		/*删除贴吧关联内容
		*/
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
		$tableModaClubCall->deleteByCharId($uid);


	}
	
///////////////////新后台	
	
	
	function getClubCountByUid($uid){
		
		return $this->findCount("uid= '".$uid."'");
	}
	
}
