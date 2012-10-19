<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_Polls extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_polls';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'poll_id';
	

	//var $manyToMany = array(
//              array(
//                  'tableClass'      => 'Table_Lankinglists',
//                  'joinTable'       => 'moda_lankinglistmessage',
//                  'foreignKey'      => 'username',
//                  'assocforeignKey' => 'list_id',
//                  'mappingName'     => 'lankinglists',
//              ),
//          );
	
	function vote($arr){
		//dump($arr);
		foreach($arr as $val){
			$ids .= $val.",";
		}	
		$ids	=	addslashes(rtrim($ids,','));
		//echo $ids;
		return $this->incrField ("poll_id IN (".$ids.")", "votes", 1);
	}
	
	/*取得年级的班级*/
	public function getNianjiBanji($nanjiid,$limit=null){
		return $this->findAll("`nianjiid`='$nianjiid'","banjiid DESC",$limit);
	}
	
	
	//in_array($id,$row['linkinglists'])
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
		if($row['thumbimg']!=""){unlink($row['thumbimg']);}
		return parent::removeByPkv($id);	
	}

}
