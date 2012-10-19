<?php
FLEA::loadClass('FLEA_Db_TableDataGateway');

class Table_ModaShows extends FLEA_Db_TableDataGateway
{

    var $tableName = 'moda_shows';

    /**
     * 该数据表的主键字段名
     *
     * @var string
     */
    var $primaryKey = 'show_id';
	
	
	function readOne(){

		//return $this->incrField ("poll_id IN (".$ids.")", "votes", 1);
	}
	
	function deleteByShowId($key)
	{
			//删除下面展示
			$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
			$num = $tableModaAttach->findCount("show_id=".$key);
			$tableModaAttach->deleteAllByShowId($key);
	
			//删除下面评论
			$TableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
			$TableModaShowDiscuss->deleteAllByShowId($key);
			
			//删除展示封面
			$this->deleteImg($key);	
			$this->removeByPkv($key);
			//$this->deleteImg($key);	
			return $num;
	}
	
	/*清空展示及相关评论
	*/
	function deleteAllByUserId($key){
	
		$showDat = $this->findAll("user_id = ".$key."");
		
		/*清空展示及相关评论
		*/
		foreach($showDat as $sdat)
		{
		
			//删除下面展示
			$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
			$tableModaAttach->deleteAllByShowId($sdat['show_id']);
			
			
			//删除下面评论
			$TableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
			$TableModaShowDiscuss->deleteAllByShowId($sdat['show_id']);
			
			//删除展示封面
			$this->deleteImg($sdat['show_id']);	
			
			//删除展示
			$this->removeByPkv($sdat['show_id']);
			
		}

		
	}
	
	
	
	
	//查询
	function getShowByUserId($user_id){

		return $this->findAll("user_id=".$user_id."");
	}
	//查询
	function getShows(){

		return $this->findAll();
	}
	function getShowsOrder(){

		return $this->findAll("available = 1 and public = 1","created_on DESC");
	}
	
	function getShowsOrderByHomePage(){

		return $this->findAll("available = 1 and public = 1","show_id DESC limit 18");
	}
	
	//查询
	function getShowByUserName($userName){

		return $this->findAll("username='".$userName."'");
	}
	
	//查询
	function getAvlShowByUserName($userName){

		return $this->findAll("username='".$userName."' and available = 1 and public = 1 and main = 0","created_on DESC");
	}
	
	//查询
	function getTempShowByUserName($userName){

		return $this->find("username='".$userName."' and available= 0");
	}
	
	function getShowByShowId($show_id,$available=1){

		return $this->find("show_id=".$show_id." and available=".$available."");
	}
	
	function getShowByShowIdNoLimit($show_id){

		return $this->find("show_id=".$show_id."");
	}
	
	
	function getShowsToPage($state='',$orderby='',$count='',$offset=''){


		return $this->findBySql("select * from moda_shows WHERE user_id=".$state." ORDER BY ".$orderby." LIMIT ".$count." OFFSET ".$offset."");

	}
	function getCountsByUserId($user_id){

		return $this->findCount("user_id=".$user_id."");
	}
	
	function getMainShowById($username){

		return $this->find("username = '".$username."' and main = 1");
	}
	function getMainShowByReallId($user_id){

		return $this->find("user_id = ".$user_id." and main = 1");
	}
	
	
	function CansleMainShow($user_id){
		$data = $this->find("user_id=".$user_id." and main = 1");
		if(!$data)
			return ;
		$da['show_id'] = $data["show_id"];
		$da['main'] = 0;
		$this->updatetar($da);
	}
	
	//插入
	function savetar($data){
			
			$upload_name = $data['uploadfilename'];
			$img = $this->do_upload($upload_name);
			if($img)
			{
				//$this->deleteImg($data['show_img']);
				$data['show_img'] = $img;
			}
			/*else
			$this->_alertLocal("图片上传失败");*/
			//往moda_user里showcount+1
			//$table = FLEA::getSingleton('Table_ModaShows');
//			$reault = $table->find("user_id='".$data['user_id']."'");
//			$result['showcount'] +=1;
//			$table->update($result);
			
			//dump($data);
			return $this->create($data);

	}
	//更新
	function updatetar($data){
			
			/*$upload_name = $data['uploadfilename'];
			$img = $this->do_upload($upload_name);
			//var_dump($img);
			if($img)
			{
				$this->deleteImg($data['show_id']);
				$data['show_img'] = $img;
			}*/
			$curdat = $this->getShowByShowIdNoLimit($data['show_id']);
			$data['created_on'] = $curdat['created_on'];
				
			return $this->updateByConditions("show_id=".$data['show_id']."",$data);

	}
	
	function failByUserId($data){
			
			$curdat = $this->find("user_id=".$data['user_id']."");
			if(!$curdat)
			{
					return true;
				}
			$data['created_on'] = $curdat['created_on'];

			return $this->updateByConditions("user_id=".$data['user_id']."",$data);

	}
	
		//更新
	function updatetar_T($data){
			
			/*$upload_name = $data['uploadfilename'];
			$img = $this->do_upload($upload_name);
	
			if($img)
			{
				$this->deleteImg($data['show_id']);
				$data['show_img'] = $img;
			}*/
			$curdat = $this->getShowByShowIdNoLimit($data['show_id']);
			$data['created_on'] = $curdat['created_on'];
				
			return $this->updateByConditions("show_id=".$data['show_id']."",$data);

	}
	
		//上传图片
	function do_upload_T($upload_name,$path)
	{
	
		//var_dump($_FILES[$upload_name]['name']);
		$file_name =  $_FILES[$upload_name]['name'];
		
		$file_postfix = substr($file_name,strrpos($file_name,"."));
		$newname	=	"/upload/showFace".time().$file_postfix;
	
		$size=$_FILES[$upload_name]['size'];

		$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
			
		if(!in_array($file_postfix,$limittyppe) || $size>102400){

			return false ;
		}else{
				 $temp_name = $_FILES[$upload_name]['tmp_name'];
				 
				 $result = move_uploaded_file($temp_name,".".$newname);
				 if($result)
				 {
					return $newname;
				 }else 
				 {
					return false;
				 }
		 }
		 
	}
	
	
	
	//上传图片
	function do_upload($upload_name)
	{
	
		//var_dump($_FILES[$upload_name]['name']);
		$file_name =  $_FILES[$upload_name]['name'];
		
		$file_postfix = substr($file_name,strrpos($file_name,"."));
		$newname	=	"/upload/showFace".time().$file_postfix;
	
		$size=$_FILES[$upload_name]['size'];

		$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
			
		if(!in_array($file_postfix,$limittyppe) || $size>102400){

			return false ;
		}else{
				 $temp_name = $_FILES[$upload_name]['tmp_name'];
				 
				 $result = move_uploaded_file($temp_name,".".$newname);
				 if($result)
				 {
					return $newname;
				 }else 
				 {
					return false;
				 }
		 }
		 
	}
	//删除图片
	function deleteImg($show_id)
	 {
	 	$one = $this->find("show_id=".$show_id."");
		//dump($one);
		//var_dump($one['show_img']);
		if($one['show_img'])
		unlink($one['show_img']);
		if($one['temp_img'])
		unlink($one['temp_img']);
		
	}


	function _alertLocal($word=""){
		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');</script>
</head><body></body></html>";
	}

	
	
	
/*新后台	
*/	
	
	function getViewsByUsername($username){
		
			return $this->findAll("available = 1 and username = '".$username."'");
		
	}
	function getViewCountByUsername($username){
		
			return $this->findCount("available = 1 and username = '".$username."'");
		
	}
	
}
