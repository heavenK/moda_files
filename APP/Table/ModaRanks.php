<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaRanks extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_ranks';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'rank_id';
	
	
	/*此表中ranke_title是UNIQUE KEY /////////////////////////////////////////////////
	*/
	
	
	
	public function save($data){	
		//$fields	=	$this->fields;
		//$checkfix	=	get_app_inf('FileExts');
		$limittyppe	=	array('.jpg','.gif','.png','.bmp');
				
		//$uploaddir	=	get_app_inf('uploadDir');
		$uploaddir ='modanewspic/';	
		
		foreach($_FILES as $upload_name => $val)
		{
				$file_name = $_FILES[$upload_name]['name'];
				if($file_name != "")
				{
						$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
						$newname	=	$uploaddir.$upload_name.rand(1000,9999).time().$file_postfix;
						//文件类型检查
						//if(in_array($file_postfix,explode(",",$checkfix))){echo "文件类型错误";return false;}
						if(in_array($file_postfix,$limittyppe))
						{
							//如果是修改信息
							if($data[$this->primaryKey]!="" and $file_name != "")
							{
								$trow	=	$this->find($data[$this->primaryKey]);
								if($trow[$upload_name] !="")
									unlink($trow[$upload_name]);
							}
							//echo $newname;
							$temp_name = $_FILES[$upload_name]['tmp_name']; 		 
							$result = move_uploaded_file($temp_name,$newname);	
							$data[$upload_name]	=	$newname;		
						}
				
				}
				
		}
		return parent::save($data);			
	}
	
	function removeByPkv($id){
		$uploaddir	=	get_app_inf('uploadDir');
		$row	=	$this->find((int)$id);
		if($row['img']!=""){unlink($row['img']);}
		return parent::removeByPkv($id);	
	}
	
	
	
	function getRankByTitle($title){
	
		$data = $this->find("rank_title='".$title."'");
		return $data;
	}
	
	function getRankById($rank_id){
	
		$data = $this->find("rank_id= ".$rank_id." ");
		return $data;
	}
	
	
	
	function getRankByRankTile($title){
	
		$data = $this->find("rank_title='".$title."'");
		
		
		return $data;
	}
	
	function delByRankId($rank_id){
	
			return $this->removeByConditions("rank_id= ".$rank_id." ");
		
	}
	
	function updatetar($data){
		
		return $this->updateByConditions("rank_id = ".$data['rank_id']."",$data);
	}
	
	function _alert($word=""){
		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');</script>
</head><body></body></html>";
	}	
	

////////////////////////新后台


	function getAllRanks($data){
		
		$data = $this->findAll("rank_id DESC");
		return $data;
	}







}
