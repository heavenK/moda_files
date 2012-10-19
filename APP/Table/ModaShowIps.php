<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaShowIps extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_showips';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'ip_id';
	
	
	function incresAll()
	{
		$suc = $this->find(" show_id = -1 ");
		if($suc)
		{
					$cha = time() - $suc['created'];
					//echo time().",".$suc['created'].",".$cha;
					if($cha>10000)//单位秒
					{
							$row2['created']	=	time();
							$row2['ip_id'] = $suc['ip_id'];
							$this->save($row2);
							
							$tableModaShows = FLEA::getSingleton('Table_ModaShows');
							$tableModaShows->incrField("available = 1","views",88);
							/*
							*/
							$this->removeByConditions("created < ".$suc['created']."");
					}
			}
		else
		{
				$row2['created']	=	time();
				$row2['show_id'] = -1;
				$this->save($row2);
			}
	}
	
	function forSearch()
	{
			$ip = $this->GetIP();
			$suc = $this->find("ip = '".$ip."'");
			if($suc)
			{
					$cha = time() - $suc['created'];
					if($cha>15)//单位秒
					{
						$row2['created'] = time();
						$row2['ip_id'] = $suc['ip_id'];
						return $this->save($row2);
						
						
						
					}
					else
						return false;
			}
			else
			{
					$row2['created'] = time();
					$row2['show_id'] = "";
					$row2['ip'] = $ip;
					return $this->save($row2);
			}
	}
	
	function getNumByIp($ip_id){
		$row	=	$this->find("ip_id='".$ip_id."'");
		return $row;
	}
	
		
	function checkip($show_id){
		$ip	=	$this->GetIP();
		$row	=	$this->find("ip='".$ip."' and show_id=".$show_id."");
		//var_dump($row);	
		if($row){
			$cha	=time()-$row['created'];
			//var_dump($cha);	
			if($cha>5000){			
				$row2['ip_id'] = $row['ip_id'];	
				//$row2['ip']	=	$ip;
				$row2['created']	=	time();
				//$row2['pollNum'] = 1;
				$this->updateByConditions("ip_id=".$row2['ip_id']."",$row2);
				return true;
			
			}else{
				return false;
			}		
		
		}else{
			$row2['ip']	=	$ip;
			$row2['created']	=	time();
			$row2['show_id'] = $show_id;
			$row2['username']	=	$_SESSION["Zend_Auth"]['storage']->username;
			//$row2['pollNum'] = 1;
			$this->save($row2);
			return true;
		}
	}


function checkDatelineIp(){
		$ip	=	$this->GetIP();
		$row	=	$this->find("ip='".$ip."'");
		//var_dump($row);	
		if($row){
			$row	=	$this->find("ip='".$ip."' and club_id=".$club_id."");
			$cha	=time()-$row['created'];
			//var_dump($cha);	
			if($cha>5000){			
				$row2['ip_id'] = $row['ip_id'];	
				//$row2['ip']	=	$ip;
				$row2['created']	=	time();
				//$row2['pollNum'] = 1;
				$this->updateByConditions("ip_id=".$row2['ip_id']."",$row2);
				return true;
			
			}else{
				return false;
			}		
		
		}else{
			$row2['ip']	=	$ip;
			$row2['created']	=	time();
			$row2['show_id'] = $show_id;
			$row2['username']	=	$_SESSION["Zend_Auth"]['storage']->username;
			//$row2['pollNum'] = 1;
			$this->save($row2);
			return true;
		}
	}
	
	
	function GetIP(){
	   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
			   $ip = getenv("HTTP_CLIENT_IP");
		   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			   $ip = getenv("HTTP_X_FORWARDED_FOR");
		   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			   $ip = getenv("REMOTE_ADDR");
		   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			   $ip = $_SERVER['REMOTE_ADDR'];
		   else
			   $ip = "unknown";
	   return($ip);
	}

}
