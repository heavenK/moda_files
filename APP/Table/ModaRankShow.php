<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaRankShow extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_rankshow';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'Id';
	
	//插入
	function savetar($data){
	
	
		return $this->create($data);
	}
	
	function getTopGirlShow(){
	
	
		$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
		$ranD = $tableModaRanks->findAll("","created_on DESC limit 1");
		
		//dump($ranD[0]['rank_id']);
	
		return $this->findAll("rank_id = ".$ranD[0]['rank_id'],"Id DESC");
		//return $this->create($data);
	}
	
	
	function getTopGirlShowByHomePage(){
	
	
		//$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
		//$ranD = $tableModaRanks->findAll("","rank_id DESC limit 1");

		return $this->findAll("rank_id = 7","Id DESC limit 12");
	}
	
	
	function getTopGirlShowById($id){
	
		return $this->findAll("rank_id = ".$id."","Id DESC limit 54");
		//return $this->create($data);
	}
	
	
	function getTopGirlShowByShowId($id){
	
		return $this->find("show_id = ".$id."");
		//return $this->create($data);
	}
	
	function getTopGirlShowByUserId($id){
	
		return $this->find("user_id = ".$id."");
		//return $this->create($data);
	}
	
	
	function getShowByMingci(){
	
		return $this->findAll("mingci > 0");
		//return $this->create($data);
	}
	
	/*根据票数排序
	*/
	function sortByTicket($rank_id){
	
		$suc = $this->find("rank_id =  ".$rank_id);
		if(!$suc)
		{
			 return "none";
			}
		
		return $this->findAll("rank_id =  ".$rank_id." ","ticket desc");
	}
	/*取消
	*/
	function NOsort($rank_id){
		
		$suc = $this->find("rank_id =  ".$rank_id);
		if(!$suc)
		{
			 return "none";
			}
		
		$data['rank_id'] = $rank_id;
		$data['mingci'] = 0;
		//dump($data);
		return $this->updateByConditions("rank_id =  ".$data['rank_id']."",$data);
	}
	
	function updatetarTicket($data){
	
		return $this->updateByConditions("show_id=".$data['show_id']."",$data);
		//return $this->create($data);
	}
	function updatetar($data){
		//dump($data);
		
		return $this->updateByConditions("rank_id = ".$data['rank_id']." and show_id = ".$data['show_id']."",$data);
	}
	function updatetar_Img($data){
		//dump($data);
		
		return $this->update($data);
	}
	
	/*删除
	*/
	function delRankShow($data){
		//dump($data);
		$suc = $this->find("rank_id = ".$data['rank_id']." and show_id = ".$data['show_id']);
		if(!$suc)
		{
			 return "none";
			}
		return $this->removeByConditions ("rank_id = ".$data['rank_id']." and show_id = ".$data['show_id']."");
	}
	function delRankShowByRankId($rank_id){
		//dump($data);
		$suc = $this->find("rank_id =  ".$rank_id);
		if(!$suc)
		{
			 return "none";
			}
		return $this->removeByConditions ("rank_id = ".$rank_id."");
	}
	
	function delRankShowByUserId($user_id){
		//dump($data);
		$cur = $this->getTopGirlShowByUserId($user_id);
		if(!$cur)
		{
				return true;
			}
		
		return $this->removeByConditions ("user_id = ".$user_id."");
	}
	
	
	function updateHeadImg($result2)
	{
		$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
		/*关联封面图片
		*/
		$temp = 0;
		foreach($result2 as $rs)
		{
			$ms = $tableModaShow->getShowByShowId($rs['show_id']);	
			$result2[$temp]['head_img'] = $ms['show_img'];
			$this->updatetar_Img($result2[$temp]);
			
			$temp++;
		}
	
	}
	
	
}
