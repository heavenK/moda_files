<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_Lankinglists extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_lankinglist';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'list_id';
	

	/*var $manyToMany = array(
              array(
                  'tableClass'      => 'Table_Polls',
                  'joinTable'       => 'moda_lankinglistmessage',
                  'foreignKey'      => 'list_id',
                  'assocforeignKey' => 'username',
                  'mappingName'     => 'polls',
              ),
          );
	*/
	
	public function save($data){	
		$fields	=	$this->fields;
		$checkfix	=	get_app_inf('FileExts');
		$uploaddir	=	get_app_inf('uploadDir');
		
		foreach($_FILES as $upload_name => $val){
			$file_name = $_FILES[$upload_name]['name'];
			if($file_name == ''){ break; }
			$file_postfix = substr($file_name,strrpos($file_name,"."));
			$newname	=	$uploaddir."/xueshengbanji".time().$file_postfix;
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
		return parent::save($data);		
		
	}
	
	function removeByPkv($id){
		$uploaddir	=	get_app_inf('uploadDir');
		$row	=	$this->find((int)$id);
		if($row['thumbimg']!=""){unlink($row['thumbimg']);}
		return parent::removeByPkv($id);	
	}

}


//<?php 
//foreach($row['linkinglists'] as $valll){
//	if($valll['list_id']==$val2['list_id']){
//		echo 'checked="checked"';
//		break; 
//	}
//}
//
//
//if(in_array($val2['list_id'],$row['linkinglists'])){ echo 'checked="checked"';} 
//
//?>