
<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaFriend extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_friend';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'uid';
	
	function addFriend($data){
		return $this->create($data);
	}
	
	function updateByCon($uid,$fuid,$data){
	
		return $this->updateByConditions("uid=".$uid." and fuid=".$fuid."", $data);
	}
	function friendCheck($uname,$fname)
	{
		return $this->find("uname='".$uname."' and fusername='".$fname."'");
	}
	
	function getFriendByUsername($uname,$status)
	{
		return $this->findAll("uname='".$uname."' and status=".$status."");
	}
	
	function getFriendApplyByUsername($fusername,$status)
	{
		$applyData = $this->findAll("fusername='".$fusername."' and status=".$status."");
		if($applyData){
			//dump($applyData);
			/*读取passport
			*
			*
			*/	
			$tablePassportUser = FLEA::getSingleton('Table_PassportUser');	
			$temp = 0;
			foreach($applyData as $data){
			
				//dump($friends[$temp]['uname']);
				$oneData = $tablePassportUser->getUserByUsername($data['uname']);
				//dump($oneData);
				//$oneData = $tablePassportUser->aaaa($data['uname']);
				$applyData[$temp]['nickname'] = $oneData['nickname'];
				$temp += 1;
			//dump($oneData);
			}
			return $applyData;
		}
		return false;
		
		
	}

	function getFriendWithModaUser($username,$status)
	{
		/*读取Table_ModaFriend
		*
		*
		*/		
		//dump($curUsername);
		//$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');
		
		$applyData = $this->getFriendByUsername($username,$status);
		
		
		//dump($applyData);
		/*读取passport
		*
		*
		*/	
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');	
		$temp = 0;
		foreach($applyData as $data){
		
			//dump($friends[$temp]['uname']);
			$oneData = $tableModaUser->getUserByUname($data['fusername']);
			if($oneData)
			{
						//dump($oneData);
						//$oneData = $tablePassportUser->aaaa($data['uname']);
						$applyData[$temp]['nickname'] = $oneData['nickname'];//为数据添加昵称
						$applyData[$temp]['head_img'] = $oneData['head_img'];
						//$temp += 1;
			}
			else
			{		
					//$myda = $tableModaUser->getUserByUname($username);
					//$this->friendDelete($myda['user_id'],);
					$applyData[$temp] = "";
			}
			$temp += 1;
		//dump($oneData);
		}
		
		return $applyData;
		
	}

	
	function friendPass($uid,$fuid)
	{
		//dump($uname);
		//dump($fusername);
		/*
			申请者状态
		*/
		$data['status'] = 1;
		$data['num']=0;
		$this->updateByConditions("uid = '".$uid."' and fuid = '".$fuid."'",$data);
		
		$friendData = $this->find("uid = '".$uid."' and fuid = '".$fuid."'");
		/*
			接受者
		*/
		$tempname = $friendData['fusername'];
		$friendData['fusername'] = $friendData['uname'];
		$friendData['uname'] = $tempname;
		$friendData['status'] = 1;
		$friendData['num'] = 1;
		
		//$tmpId = $friendData['uid'];
		$friendData['uid'] = $fuid;
		$friendData['fuid'] = $uid;
		
		$this->create($friendData);
	}
	
	function friendIgnore($uid,$fuid)
	{
		$this->removeByConditions("uid = '".$uid."' and fuid = '".$fuid."'");
	}
	
	function friendDelete($uid,$fuid)
	{
		$this->friendDeleteOrder($fuid,$uid);
		$this->removeByConditions("uid = ".$uid." and fuid = ".$fuid."");
		//$this->removeByConditions("fuid = ".$fuid." and uid = ".$uid."");
		
	}
	
	function friendDeleteOrder($uid,$fuid)
	{
		//$this->removeByConditions("uid = ".$uid." and fuid = ".$fuid."");
		$this->removeByConditions("fuid = ".$fuid." and uid = ".$uid."");
		
	}
	
}
