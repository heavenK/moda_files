<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaUser extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_users';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'user_id';
	
	
	var $hasMany = array(
              array(
			  	  //'enabled'	=>	false,
                  'tableClass'  => 'Table_ModaRankMes',
                  'foreignKey'  => 'mesid',
                  'mappingName' => 'touxian',
				  'sort'		=>	'mc asc,rank_id desc',
				  //'conditions'	=>	"",
				  'limit'		=>	'1',
              ),
    );
	
	public function save($data)
	{	
		$fields	=	$this->fields;
		$checkfix	=	get_app_inf('FileExts');
		//$uploaddir	=	get_app_inf('uploadDir');
		$uploaddir ='modaupload/';	
		
		foreach($_FILES as $upload_name => $val)
		{
				$file_name = $_FILES[$upload_name]['name'];
				if($file_name == '')
				{ break; }
				$file_postfix = substr($file_name,strrpos($file_name,"."));
				$newname	=	$uploaddir."/".rand(1000,9999).time().$file_postfix;
				//文件类型检查
				if(in_array($file_postfix,explode(",",$checkfix))){echo "文件类型错误";return false;}
				//如果是修改信息
				if($data[$this->primaryKey]!="" and $file_name != ''){
					$trow	=	$this->find($data[$this->primaryKey]);
					unlink($trow[$upload_name]);
				}
				//echo $newname;
				$temp_name = $_FILES[$upload_name]['tmp_name']; 		 
				$result = move_uploaded_file($temp_name,$newname);	
				$data[$upload_name]	=	$newname;		
		}

		return parent::save($data);		
		
	}
	
	function removeByPkv($id){
		$uploaddir	=	get_app_inf('uploadDir');
		$row	=	$this->find((int)$id);
		if($row['img1']!=""){unlink($row['img1']);}
		if($row['img2']!=""){unlink($row['img2']);}
		if($row['img3']!=""){unlink($row['img3']);}
		if($row['img4']!=""){unlink($row['img4']);}
		if($row['img5']!=""){unlink($row['img5']);}
		if($row['img6']!=""){unlink($row['img6']);}
		return parent::removeByPkv($id);	
	}
	
	public function getAllUsers(){	
		return $this->findAll();
	}
	
	//查询
	public function getUserByUname($username)
	{
		return $this->find("username = '".$username."' and (pass = 1 or pass = 11)");
	}
	
	//查询
	public function getUserByUnamePass($username)
	{
		return $this->find("username = '".$username."' and (pass = 1 or pass = 11)");
	}
	
	//查询
	public function getUserByUserId($user_id)
	{
		return $this->find("user_id = ".$user_id." and ( pass = 1 or pass = 11)");
	}
	
	//查询
	public function getUserByUnameNoLimit($username)
	{
		return $this->find("username = '".$username."'");
	}
	
	//查询
	public function getUserByNickName($nickname)
	{
		//return $this->findAll("nickname like '%".$nickname."%'");
		return $this->findAll("nickname like '%".$nickname."%'");
	}
	
	
		//查询
	public function getUserIDByAddress($address)
	{
		//return $this->findAll("nickname like '%".$nickname."%'");
		return $this->find("address = '".$address."'");
	}
	
	
	
	
	
	
	
	
	
	

}
