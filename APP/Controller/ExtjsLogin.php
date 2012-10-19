<?php
FLEA::loadClass('Controller_BoBase');
class Controller_ExtjsLogin extends Controller_BoBase
{
	function actionindex(){
		echo "{success:true,message:\"成功\"}";
	}	

	
	/*动作，添加美达用户
	*/	
	function actionAdminLogin(){
			
			
//		$this->connect_to_db();
//
		$user = $_POST['userName'];
		//$pass = $_POST['password'];
//
//		$success = $this->login($user, $pass);
//		
		$user = iconv("UTF-8","gb2312",$user);
		//echo $user;

		if($user == "我爱美达")
		{
				$_SESSION["modaAdmin"]->modaAdmin = 1;
				echo "true";
		}
		else
		{
				echo "{success:false,message:\"口令错误\"}";
			}
				
//				
//		if($user == "aaa" && $pass == "chobits")
//		{
//				$_SESSION["Zend_Auth"]['storage']->modaAdmin = 1;
//		
//				echo "true";
//			}
//			else
//			{
//		
//				echo "{success:false,message:\"失败\"}";
//				}
		
		
	}
	
	function actionImgRAR()
	{
		exit;
		
		set_time_limit(240);
		
		$modauser = FLEA::getSingleton('Table_ModaUser');
		
		$alluser = $modauser->getAllUsers();
		
		$cont = $modauser->findCount(); 
		
		dump($cont);
		$i = 0 ;

		foreach($alluser as $user)
		{
			if($user['img1'] != ""){
				$this->_funRAR($user['img1']);
			}
			if($user['img2'] != ""){
				$this->_funRAR($user['img2']);
			}
			if($user['img3'] != ""){
				$this->_funRAR($user['img3']);
			}
			if($user['img4'] != ""){
				$this->_funRAR($user['img4']);
			}
			$i++;
			dump($i);
		
		}
	
	}
/*
*/
	function _funRAR($url ="")
	{
	
            /*压缩1
			*/
			$dst_h = 620;
			$this->_todoRAR($url,0,$dst_h);
			
			/*压缩2
			*/				
							
			$dst_w = 80;
			$dst_h = 60;
			$this->_todoRAR($url,$dst_w,$dst_h,1);
	
			//return $url_view;
	}
	
/*
*/	
	function _todoRAR($url,$dst_w,$dst_h,$do = 0)
	{
		
			
	
			$file_postfix = strtolower(substr($url,strrpos($url,".")));
			
			//dump($file_postfix);
			
			if($file_postfix == ".gif"){
					$src_img = imagecreatefromgif($url);
			}
			elseif($file_postfix == ".jpg" || $file_postfix == ".jpeg"){
					$src_img = imagecreatefromjpeg($url);
			}
			elseif($file_postfix == ".png"){
					$src_img = imagecreatefrompng($url);
			}
			elseif($file_postfix == ".bmp"){
					//$src_img = imagecreatefromjpeg($url);
					return;
			}
			else
			{
				//unlink($trow[$upload_name]);
				return ;
				}
			
			//dump($url);
			list($w, $h, $type, $attr)=getimagesize($url);
			
			if($h < $dst_h)
			{
				return;
				}
			
			
			$dst_w=$dst_h*$w/$h;
			//$src_h = $w*$dst_h/$dst_w;
			$jpeg_quality = 100;
			$dst_img = imagecreatetruecolor($dst_w,$dst_h); 
			
			
			
			$suc = imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_w,$dst_h,$w,$h);

			if($suc)
			{
				if($do == 1)
				{
					//session_start(); 
					//$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
					//$mark=$_SESSION['random_key'];
					
					$miniurl = substr($url,0,strrpos($url,".")); 
					$miniurl = $miniurl."_mini_".$file_postfix;
					
					$filename = $miniurl;
					//dump($filename);
					//return;
				}else
				{
					$filename = $url;
				}
			}
							
			if($file_postfix == ".gif"){
					imagegif($dst_img,$filename);
			}
			elseif($file_postfix == ".jpg" || $file_postfix == ".jpeg"){
					imagejpeg($dst_img,$filename,$jpeg_quality);
			}
			elseif($file_postfix == ".png"){
					imagepng($dst_img,$filename);
			}
			
			return $filename;
			
	}	




	
/*	统一生产缩略图片
*/	
	function actionRAR()
	{
		//echo "ddd";exit;
		$tableShowContent = FLEA::getSingleton('KfcTable_ShowContent');
		//if($_GET["id"])
		
		$AllShow = $tableShowContent->findAll("show_id=".$_GET["id"]);
		//dump($AllShow );
		if(count($AllShow)==0){$this->_changePage("?Controller=Admin&action=RAR&id=".($_GET["id"]+1)."");}
		
		//dump($AllShow);
		
		if($AllShow)
		{
			foreach($AllShow as $show)
			{
			
			//	生成
			//dump($show);
				if($show['face_url'] != "")
				{
					
					
					
							//按比例缩小
//							
							list($w, $h, $type, $attr)=getimagesize("./".$show['face_url']);
/*							$w = 508;
//							$h = 635;
*/							
							$dst_w = 114;
							$dst_h = 142;
							$src_h = $w*$dst_h/$dst_w;
							$jpeg_quality = 100;
							
							
							$file_postfix = strtolower(substr($show['face_url'],strrpos($show['face_url'],".")));
							$dst_img = imagecreatetruecolor($dst_w,$dst_h); 
							
							
							if($file_postfix == ".gif"){
								$src_img = imagecreatefromgif($show['face_url']);
							}
							elseif($file_postfix == ".jpg"){
								$src_img = imagecreatefromjpeg($show['face_url']);
							}
							elseif($file_postfix == ".png"){
								$src_img = imagecreatefrompng($show['face_url']);
							}
							
							//$src_img = imagecreatefromjpeg($show['face_url']);	
							$suc = imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_w,$dst_h,$w,$src_h);
							
							
							//dump($file_postfix);
							if($suc)
							{
								//dump($show['show_id']);
								$filename = "showFaceUpload/url_view_".$show['show_id'].$file_postfix;
							}
							
							if($file_postfix == ".gif"){
								imagegif($dst_img,$filename);
							}
							elseif($file_postfix == ".jpg"){
								imagejpeg($dst_img,$filename,$jpeg_quality);
							}
							elseif($file_postfix == ".png"){
								imagepng($dst_img,$filename);
							}
							
							
//							
							//$show['show_id'] = $show_id;
							$show['url_view'] = $filename;
							$tableShowContent->savetar($show);
							
							exit;
							$this->_changePage("?Controller=Admin&action=RAR&id=".($_GET["id"]+1)."");
							//echo $show['show_id']."号生成成功!";echo "<a href='?Controller=Admin&action=RAR&id=".($_GET["id"]+1)."'>下一个</a>";
							
							
							
				}
				
			}
		}
		

	}	
	
	
	
	



}
?>