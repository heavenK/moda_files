<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaEvent extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_event';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'event_id';
	
	public function save($data)
	{	
			$fields	=	$this->fields;
			//$checkfix	=	get_app_inf('FileExts');
			//$uploaddir	=	get_app_inf('uploadDir');
			$uploaddir ='modanewspic/';	
			
			foreach($_FILES as $upload_name => $val)
			{
					$file_name = $_FILES[$upload_name]['name'];
					if($file_name == ''){ break; }
					$file_postfix = substr($file_name,strrpos($file_name,"."));
					$newname	=	$uploaddir."/modaEvent".rand(1000,9999).time().$file_postfix;
					//文件类型检查
					//if(in_array($file_postfix,explode(",",$checkfix))){echo "文件类型错误";return false;}
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
		if($row['img_url']!=""){unlink($row['img_url']);}
		if($row['url']!=""){unlink($row['url']);}
		return parent::removeByPkv($id);	
	}

	function delByEventId($id){

		$row	=	$this->find("event_id = ".$id."");
		
		if(!$row)
		{
			 return "none";
			}
		
		
		if($row['home_img']!=""){unlink($row['home_img']);}
		if($row['index_img']!=""){unlink($row['index_img']);}
		if($row['header_img']!=""){unlink($row['header_img']);}
		//dump($row);
		return $this->removeByPkv($id);
		
		
	}	
	

}
