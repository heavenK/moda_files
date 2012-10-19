<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaTouXian extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_touxian';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'id';
	
	
	public function save($data){	
		$fields	=	$this->fields;
		$checkfix	=	get_app_inf('FileExts');
		//$uploaddir	=	get_app_inf('uploadDir');
		$uploaddir ='modanewspic/';	
		
		foreach($_FILES as $upload_name => $val){
			$file_name = $_FILES[$upload_name]['name'];
			if($file_name == ''){ break; }
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
		//foreach($data as	$k1=> $v1){$data[$k1]	=	addslashes($v1);}
		//if(is_array($data['lankinglists']) and count($data['lankinglists'])>0){
//			$sql	=	"INSERT INTO moda_lankinglistmessage (`list_id`,`username`) VALUES ";
//			foreach($data['lankinglists'] as $val){
//				$sql .=  "('".$val."','".$data['username']."') ,";
//			}
//			$sql =	rtrim($sql,',');
//			$this->execute($sql);
//		
//		}
		return parent::save($data);		
		
	}
	
	function removeByPkv($id){
		$uploaddir	=	get_app_inf('uploadDir');
		$row	=	$this->find((int)$id);
		if($row['touxian']!=""){unlink($row['touxian']);}
		return parent::removeByPkv($id);	
	}
	

}
