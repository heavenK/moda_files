<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_Pollips extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_pollips';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'pollip_id';
	
	
	function checkip(){
		$ip	=	$this->GetIP();
		$row	=	$this->find("ip='".$ip."' or username='".$_SESSION["Zend_Auth"]['storage']->username."'");
			
		if($row){
			$cha	=time()-$row['created'];
			if($cha>86400){			
				$row['created']	=	time();
				//dump($row);
				$this->save($row);
				return true;
			}else{
				return false;
			}		
			return false;
		}else{
			$row2['ip']	=	$ip;
			$row2['created']	=	time();
			$row2['username']	=	$_SESSION["Zend_Auth"]['storage']->username;
			$this->save($row2);
			return true;
		}
	}

	/*取得年级的班级*/
	public function getNianjiBanji($nanjiid,$limit=null){
		return $this->findAll("`nianjiid`='$nianjiid'","banjiid DESC",$limit);
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
