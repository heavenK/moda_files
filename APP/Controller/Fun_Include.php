<?php
function FI_alert($word="",$url="",$target="self")
{
		if($_REQUEST["forward"]!=''){
			$url	=	$_REQUEST["forward"];
		}elseif($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		//FI_errorPage($url);
		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');$target.location='$url';</script>
</head><body></body></html>";
		exit;
}
function FI_changePage($url="",$target="self")
{
		if($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script type=\"text/javascript\" >$target.location='$url';</script>
</head><body></body></html>";
		exit;
}

function FI_errorPage($url="",$errormsg="",$target="self")
{
		include(APP_DIR."/LichiViews/404.htm");
		exit;
}
/*��֤�Ƿ����չʾ
*/	
function FI_ShowExist($show_id,$notice)
{
			if(!$show_id)
					FI_alert('ȱ��չʾID',"/");
			$tableModaShows = FLEA::getSingleton('Table_ModaShows');
			$showDE = $tableModaShows->getShowByShowIdNoLimit($show_id);
			if($showDE)
					return $showDE;
			else
					$msg = false;
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*��֤�Ƿ����չʾ�����Ƿ�չʾ����Ȩ�ޣ�����չʾ����
*/	
function FI_ShowAdimnRoot($show_id,$notice)
{
			$showDE = FI_ShowExist($show_id,$notice);
			$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
			if(!$_SESSION["Zend_Auth"]['storage']->username)
			{
				if($notice)
					FI_alert('���޹���Ȩ��',"/");
				else
					return false;
			}
			$userda = $Table_ModaUser->find("username = '".$_SESSION["Zend_Auth"]['storage']->username."'");
			
			
			if($showDE['user_id'] == $userda['user_id'])
			{
				if($showDE['main'] == 1)
				{
					if(FI_CheckAdminRoot())
						return $showDE;
					else
						$msg = false;
				}
				else		
					return $showDE;
			}
			else
			{
				if(FI_CheckAdminRoot())
					return $showDE;
				else
					$msg = false;
				
			}
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*��õ�ǰϵͳ�û�ID
*/
function Fi_GetCurUserId()
{
		$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
			if(!$_SESSION["Zend_Auth"]['storage']->username)
					return false;
		$tableModaUser = FLEA::getSingleton('Table_LichiUser');
		$userDat = $tableModaUser->getUserByUname($curUsername);
		return $userDat['user_id'];
}

/*ͨ��֤�û�
*/	
function FI_TestComUser($username,$uid,$status,$notice)
{
		if($username)
		{
				$table=FLEA::getSingleton('Table_PassportUser');
				$result=$table->find("username='".$username."'" );
		}
		elseif($uid)
		{
				$table=FLEA::getSingleton('Table_PassportUser');
				$result=$table->find("uid='".$uid."'" );
		}
		/*
		*/
		if($notice)
		{
				if(!$result)
					FI_alert("��û�е�¼�����¼���ύ���룡","http://passport.we54.com/Index/login?forward=".urlencode("http://lichi.we54.com/"));
				else
					return $result;
		}
		else
		{
				if(!$result)
					return false;
				else
					return $result;
		}
}

/*ϵͳ�û�
*/	
function FI_TestSysUser($username,$user_id,$status,$notice)
{
		$table = FLEA::getSingleton('Table_ModaUser');
		if($username)
		{
				if($status == -1)
					$result = $table->find("username='".$username."'");
				else
					$result = $table->find("username='".$username."'And (pass = 11 or pass = ".$status.")");
		}
		elseif($user_id)
		{
				if($status == -1)
					$result = $table->find("user_id = ".$user_id);
				else
					$result = $table->find("user_id = ".$user_id." And (pass = 11 or pass = ".$status.")");
		}
		if($notice)
		{
				if(!$result)
					FI_alert('���û�������',"/");
				else
					return $result;
		}
		else
		{
				if(!$result)
					return false;
				else
					return "aaaa";
		}
}
/*����
*/
function FI_CheckCommonRight($user_id,$notice)
{
		if(FI_CheckAdminRoot())
			return true;
		if(!$_SESSION["Zend_Auth"]['storage']->username)
		{
			if($notice)
				FI_alert('���޹���Ȩ��',"/");
			else
				return false;
		}
		$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
		$userda = $Table_ModaUser->find("username = '".$_SESSION["Zend_Auth"]['storage']->username."'");
	
		
		if($user_id)
			if($user_id == $userda['user_id'])
				return true;
		if($notice)
				if(!$msg)
					FI_alert('���޹���Ȩ��',"/");
		else
				if(!$msg)
					return false;
}
/*����Ա�û�
*/
function FI_CheckAdminRoot()
{
		if($_SESSION["Zend_Auth"]['storage']->username == 'moda' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='aaa' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='NewYouth' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='����زز����' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='iloveyou' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='��������' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='С�����' )
			return true;
		return false;		
}
/*��֤�Ƿ����չʾ
*/	
function FI_MusicExist($id,$notice)
{
			if(!$id)
					FI_alert('ȱ��ID',"/");
			$Table_nymcMusic = FLEA::getSingleton('Table_nymcMusic');
			$showDE = $Table_nymcMusic->find("id = ".$id);
			if($showDE)
					return $showDE;
			else
					$msg = false;
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*��֤�Ƿ����չʾ�����Ƿ�չʾ����Ȩ�ޣ�����չʾ����
*/	
function FI_MusicAdimnRoot($id,$notice)
{
			$showDE = FI_MusicExist($id,$notice);
			if($showDE['username'] == $_SESSION["Zend_Auth"]['storage']->username)
				return $showDE;
			else
			{
				if(FI_CheckAdminRoot())
					return $showDE;
				else
					$msg = false;
				
			}
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*��֤�Ƿ����
*/	
function FI_DiscussExist($dis_id,$notice)
{
			if(!$dis_id)
					FI_alert('ȱ��չʾID',"/");
			$tableModaShows = FLEA::getSingleton('Table_LichiShowDiscuss');
			$showDE = $tableModaShows->getShowDiscussByID($dis_id);
			if($showDE)
					return $showDE;
			else
					$msg = false;
			if($notice)
					if(!$msg)
							FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
							return false;
}

/*��֤�Ƿ�������ۣ����Ƿ����Ȩ��
*/	
function FI_DiscussAdimnRoot($dis_id,$notice)
{
			$showDE = FI_DiscussExist($dis_id,$notice);
			if($showDE['uname'] == $_SESSION["Zend_Auth"]['storage']->username)
				return $showDE;
			else
				$msg = false;
			if($notice)
					if(!$msg)
						FI_alert('���޹���Ȩ��',"/");
			else
					if(!$msg)
						return false;
}
/*
*/	
function FI_MusicFileUpLoad($upload_name,$dir,$backurl,$notice)
{
		$limittyppe	=	array('.mp3');
		$file_name = $_FILES[$upload_name]['name'];
		$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
		$uploadfile = $dir.$file_postfix;
		/*
		*/
		if(!in_array($file_postfix,$limittyppe))
		{
				if($notice)
						FI_alert("���ύmp3��ʽ�ļ���",$backurl);
				else
						return false;
		}
//		if($_FILES[$upload_name]['size']>2048000 || $_FILES[$upload_name]['size'] == 0)
//		{
//				if($notice)
//						FI_alert("ͼƬ���ܳ���2M��",$backurl);
//				else
//						return false;
//		}
		if (move_uploaded_file($_FILES[$upload_name]['tmp_name'], $uploadfile)) 
				return $uploadfile;
		if($notice)
				FI_alert('ʧ��',"/");
		else
				return false;
}
/*
*/	
function FI_ImgUpLoad($upload_name,$dir,$dst_w,$dst_h,$backurl,$notice,$done = "")//$done ǿ��ѹ��
{
		$limittyppe	=	array('.jpg','.gif','.png');
		$file_name = $_FILES[$upload_name]['name'];
		$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
		$uploadfile = $dir.$file_postfix;
		/*
		*/
		if(!in_array($file_postfix,$limittyppe))
		{
				if($notice)
						FI_alert("���ύjpg,gif,png��ʽ��ͼƬ��",$backurl);
				else
						return false;
		}
		if($_FILES[$upload_name]['size']>2048000 || $_FILES[$upload_name]['size'] == 0)
		{
				if($notice)
						FI_alert("ͼƬ���ܳ���2M��",$backurl);
				else
						return false;
		}
		/*
		*/
		if (move_uploaded_file($_FILES[$upload_name]['tmp_name'], $uploadfile)) 
		{
				/*
				*/
				if($file_postfix == ".gif")
					$src_img = imagecreatefromgif($uploadfile);
				elseif($file_postfix == ".jpg")
					$src_img = imagecreatefromjpeg($uploadfile);
				elseif($file_postfix == ".png")
					$src_img = imagecreatefrompng($uploadfile);
				/*
				*/
				list($w, $h, $type, $attr)=getimagesize($uploadfile);//PHP����
				
				if($done)
				{
					$src_h = $w*$dst_h/$dst_w;
				}
				else
				{
						if($w < $dst_w)
						{
							$dst_w = $w;
							
							if($h < $dst_h)
							{
								$dst_h = $h;
								$src_h = $h;
							}
							else
								$src_h = $w*$dst_h/$dst_w;
						}
						else
						{
							$tem_h = $dst_w*$h/$w;//ԭͼ������
							
							if($tem_h < $dst_h)
							{
								$src_h = $h;
								$dst_h = $tem_h;
							}
							else
							{
								$src_h = $w*$dst_h/$dst_w;
							}
						}
				}
				$dst_img = imagecreatetruecolor($dst_w,$dst_h); 
				$suc = imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_w,$dst_h,$w,$src_h);
				if($suc)
				{
						if($file_postfix == ".gif")
							imagegif($dst_img,$uploadfile);
						elseif($file_postfix == ".jpg")
							imagejpeg($dst_img,$uploadfile,100);
						elseif($file_postfix == ".png")
							imagepng($dst_img,$uploadfile);
				}
				return $uploadfile;
		}
		//exit;
		if($notice)
				FI_alert('ʧ��',"/");
		else
				return false;
		
}

/*
*/	
function FI_directUpLoad($upload_name,$dir,$backurl,$notice)
{
		$limittyppe	=	array('.jpg','.gif','.png');
		$file_name = $_FILES[$upload_name]['name'];
		$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
		$uploadfile = $dir.rand(1000,9999).time().$file_postfix;
		
		if(!in_array($file_postfix,$limittyppe))
		{
				if($notice)
						FI_alert($notice,$backurl);
				else
						return false;
		}
		if($_FILES[$upload_name]['size']>2048000 || $_FILES[$upload_name]['size'] == 0)
		{
				if($notice)
						FI_alert("ͼƬ���ܳ���2M��",$backurl);
				else
						return false;
		}
		
		if (move_uploaded_file($_FILES[$upload_name]['tmp_name'], $uploadfile)) 
			return $uploadfile;
			
		if($notice)
			FI_alert('ʧ��',"/");
		else
			return false;
		
}
/*
*/	
function FI_ComImgUpLoad($tablename,$data)
{
		$thetable = FLEA::getSingleton($tablename);
		$limittyppe	=	array('.jpg','.gif','.png','.bmp');
		$uploaddir ='lichiimgs/';	
		foreach($_FILES as $upload_name => $val)
		{
				$file_name = $_FILES[$upload_name]['name'];
				if($file_name != "")
				{
						$file_postfix = strtolower(substr($file_name,strrpos($file_name,".")));
						$newname	=	$uploaddir.$upload_name.rand(1000,9999).time().$file_postfix;
						//�ļ����ͼ��
						if(in_array($file_postfix,$limittyppe))
						{
							//������޸���Ϣ
							if($data[$thetable->primaryKey]!="" and $file_name != "")
							{
								$trow	=	$thetable->find($data[$thetable->primaryKey]);
								if($trow[$upload_name] !="")
									unlink($trow[$upload_name]);
							}
							$temp_name = $_FILES[$upload_name]['tmp_name']; 		 
							$result = move_uploaded_file($temp_name,$newname);	
							$data[$upload_name]	=	$newname;		
						}
				}
				
		}
		return $data;
}
/*
*/	
function FI_CutImgUpLoad($src,$targ_x,$targ_y,$targ_w,$targ_h,$backurl,$notice,$fd_w = "",$fd_h = "")
{
		  $jpeg_quality = 100;
		  $file_postfix = strtolower(substr($src,strrpos($src,".")));
		  if($file_postfix == ".gif")
			  $img_r = imagecreatefromgif($src);
		  elseif($file_postfix == ".jpg")
			  $img_r = imagecreatefromjpeg($src);
		  elseif($file_postfix == ".png")
			  $img_r = imagecreatefrompng($src);
		  else
			  FI_alert("���ύjpg,gif,png��ʽ��ͼƬ��",$backurl);
		  /*����
		  */
		  $dst_r = imagecreatetruecolor( $targ_w,$targ_h );
		  $suc = imagecopymerge ($dst_r,$img_r,0,0,$targ_x,$targ_y,$targ_w,$targ_h,$jpeg_quality);
		  
		  if($fd_w != "" && $fd_w != "" )
		  {
		  		$new_r = imagecreatetruecolor( $fd_w,$fd_h );
				$suc = imagecopyresampled($new_r,$dst_r,0,0,0,0,$fd_w,$fd_h,$targ_w,$targ_h);
				$dst_r = $new_r;
		  }
		  if($suc)
		  {
				  if($file_postfix == ".gif")
						  $suc = imagegif($dst_r,$src);
				  elseif($file_postfix == ".jpg")
						  $suc = imagejpeg($dst_r,$src,$jpeg_quality);
				  elseif($file_postfix == ".png")
						  $suc = imagepng($dst_r,$src);
				  return $src;
		  }
		if($notice)
				FI_alert('ʧ��',$backurl);
		else
				return false;
}
/*�ַ�����
*/
function Fi_ConvertChars($text)
{
		$text = str_ireplace("rgb","'rgb'",$text);
		$text = str_ireplace("script","'script'",$text);
	    $text = str_ireplace("#","'#'",$text);
	    $text = str_ireplace("@","'@'",$text);
	    $text = str_ireplace("$","'$'",$text);
	    $text = str_ireplace("%","'%'",$text);
	    $text = str_ireplace("^","'^'",$text);
	    $text = str_ireplace("*","'*'",$text);
	    $text = str_ireplace("|","'|'",$text);
		return $text;
}
/*�ַ�����
*/
function Fi_ConvertSPchar($arr,$name)
{
		$temp = 0;
		foreach($arr as $data)
		{
			 $arr[$temp][$name] = str_ireplace("rgb","'rgb'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("script","'script'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("\r\n"," ",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("&nbsp;"," ",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("class","'class'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("style","'style'",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("span","p",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("strong","p",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h1","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h2","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h3","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h4","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h5","h",$arr[$temp][$name]);
			 $arr[$temp][$name] = str_ireplace("h6","h",$arr[$temp][$name]);
			 $temp++;
		}
		return $arr;
}

/*�����ֶΣ���̳ͷ��
*/
function Fi_CdbmembersSet($arr,$cmn = "")
{
		  $tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
		  $temp = 0;
		  foreach($arr as $data)
		  {
				  $oneData = $tableCdbmembers->_getUserByUsername($data['uname']);
				  $arr[$temp]['groupid'] = $oneData['groupid'];
				  $arr[$temp]['cdbmem_id'] = $oneData['uid'];
				  if($arr[$temp]['groupid']==4)
				  {
				  		$arr[$temp]['content'] = "���û���Ϣ�ѱ�����";
				  }
				  /*
				  */
				  if($data['head_img'] == "")		
				  {
						  $tableCdbmemberfields = FLEA::getSingleton('Table_Cdbmemberfields');
						  $cdbmfi = $tableCdbmemberfields->_getUserByUID($oneData['uid']);
						  if(strstr($cdbmfi['avatar'], "http"))
								  $arr[$temp]['head_img'] = $cdbmfi['avatar'];
						  else
						  {
								  if($cdbmfi['avatar'])
									  	$arr[$temp]['head_img'] = "http://bbs.we54.com/".$cdbmfi['avatar'];
								  else
									 	$arr[$temp]['head_img'] = "images/defhead.jpg";
						  }
				  }	
				  /*
				  */
				  if($cmn)
					 	$arr[$temp][$cmn] = str_ireplace("\r\n"," ",$data[$cmn]);
				  $temp += 1;
		  }
		  return $arr;
}

function Fi_cut_str($sourcestr,$cutlength) 
{ 
		   $returnstr=''; 
		   $i=0; 
		   $n=0; 
		   $str_length=strlen($sourcestr);//�ַ������ֽ��� 
		   while (($n<$cutlength) and ($i<=$str_length)) 
		   { 
			  $temp_str=substr($sourcestr,$i,1); 
			  $ascnum=Ord($temp_str);//�õ��ַ����е�$iλ�ַ���ascii�� 
			  if ($ascnum>=224)    //���ASCIIλ����224��
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,3); //����UTF-8����淶����3���������ַ���Ϊ�����ַ�         
				 $i=$i+3;            //ʵ��Byte��Ϊ3
				 $n++;            //�ִ����ȼ�1
			  }
			  elseif ($ascnum>=192) //���ASCIIλ����192��
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,2); //����UTF-8����淶����2���������ַ���Ϊ�����ַ� 
				 $i=$i+2;            //ʵ��Byte��Ϊ2
				 $n++;            //�ִ����ȼ�1
			  }
			  elseif ($ascnum>=65 && $ascnum<=90) //����Ǵ�д��ĸ��
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,1); 
				 $i=$i+1;            //ʵ�ʵ�Byte���Լ�1��
				 $n++;            //�������������ۣ���д��ĸ�Ƴ�һ����λ�ַ�
			  }
			  else                //��������£�����Сд��ĸ�Ͱ�Ǳ����ţ�
			  { 
				 $returnstr=$returnstr.substr($sourcestr,$i,1); 
				 $i=$i+1;            //ʵ�ʵ�Byte����1��
				 $n=$n+0.5;        //Сд��ĸ�Ͱ�Ǳ���������λ�ַ���...
			  } 
		   } 
				 if ($str_length>$cutlength){
				  $returnstr = $returnstr . "..";//��������ʱ��β������ʡ�Ժ�
			  }
			return $returnstr; 
}

function Fi_incresActiveNum($username = "" ,$num = "1",$limit = "")
{
			$Table_LichiShowIps = FLEA::getSingleton('Table_LichiShowIps');
			if(!$username)
					$username = $_SESSION["Zend_Auth"]['storage']->username;
			$Table_LichiShowIps->incresActiveNum($username,$num,$limit);
}
function Fi_alertShortMsg()
{
			$Table_Cdbpms = FLEA::getSingleton('Table_Cdbpms');
			$suc = $Table_Cdbpms->find("folder = 'inbox' and msgtoid =".$_SESSION["Zend_Auth"]['storage']->uid);
			return $suc;
}
function Fi_SendShortMsg($fromuname = "",$touname = "",$touid = "",$title = "",$msg = "")
{
			$userfrom = FI_TestComUser($fromuname,"",-1,1);
			if($touname)
			{
				$userto = FI_TestComUser($touname,"",-1,1);
				$dat['msgtoid'] = $userto['uid'];
			}
			if($touid)
				$dat['msgtoid'] = $touid;
			$dat['msgfrom'] = $userfrom['nickname'];
			$dat['msgfromid'] = $userfrom['uid'];
			$dat['folder'] = "inbox";
			$dat['new'] = 1;
			$dat['subject'] = $title;
			$dat['dateline'] = time();
			$dat['message'] = $msg;
			$dat['delstatus'] = 0;
			$Table_Cdbpms = FLEA::getSingleton('Table_Cdbpms');
			$suc = $Table_Cdbpms->save($dat);
			return $suc;
}

function Fi_upshowsdata($data)
{
			$tableModaShows = FLEA::getSingleton('Table_LichiShows');
			$suc = $tableModaShows->updateByConditions("username = '".$data['username']."'",$data);
}



function authcode($string, $operation, $key = '') {

	$auth_key = md5('71c978FGaY8GIIUy'.$_SERVER['HTTP_USER_AGENT']);  
	$key = md5($key ? $key : $auth_key);
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}

}


function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}




function FI_get_session_by_cookie()
{
			$_DCOOKIE = $_COOKIE;
			list($discuz_pw, $discuz_secques, $discuz_uid) = empty($_DCOOKIE['cdb_auth']) ? array('', '', 0) : daddslashes(explode("\t", authcode($_DCOOKIE['cdb_auth'], 'DECODE')), 1);
			$cdb_uid = $discuz_uid;
			if($discuz_uid)
			{
					$Table_We54userview = FLEA::getSingleton('Table_We54userview');
					$one = $Table_We54userview->find("cdb_uid = '".$cdb_uid."'");
					if($one)
					{
							foreach($one as $key => $value)
							{
									$_SESSION['Zend_Auth']['storage']->$key = $value; 
							}
					}
			}
}


function FI_getuserinfo($id,$note,$forward)
{
		$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
		$user = $Table_ModaUser->find("user_id = ".$id);
		if($note && !$user)
			FI_alert("��������",$forward);
		return $user;
}


function FI_getmydata($note,$forward)
{
		$data = $_SESSION['Zend_Auth']['storage'];
		if($note && !$data)
			FI_alert("��û�е�¼�����¼�������",$forward);
		return $data;
}


function FI_updatemodaext($user_id,$username,$huoyuedu,$timehuoyuedu = 0,$fensishu = 0)
{
		$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
		if($user_id)
			$modauser = $Table_ModaUser->find("user_id = ".$user_id);
		if($username)
			$modauser = $Table_ModaUser->find("username = '".$username."'");
		$Table_ModaExt = FLEA::getSingleton('Table_ModaExt');
		$extdata = $Table_ModaExt->find("user_id = ".$modauser['user_id']);
		if($timehuoyuedu)
		{
			if($extdata)
			{
					$cha = time() - $extdata['time'];
					if($cha>14400)
					{			
						$extdata['time'] = time();
						$huoyuedu += $timehuoyuedu;
					}
			}
			else
			{
					$extdata['time'] = time();
					$huoyuedu += $timehuoyuedu;
			}
		}
		$extdata['user_id'] = $modauser['user_id'];
		$extdata['username'] = $modauser['username'];
		
		$extdata['huoyuedu'] = $extdata['huoyuedu']+$huoyuedu;
		$extdata['fensishu'] = $extdata['fensishu']+$fensishu;
		
		$suc = $Table_ModaExt->updateext($extdata);
		return $suc;
}



function FI_getuseramin($user_id = '')
{
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		if($userName == '')
				return false;
		else
		{	
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				if($user_id)
				{
						$ShowUser = $tableModaUser->getUserByUserId($user_id);
						if($userName == $ShowUser['username'])
							return true;
						else
							return false;
				}
				else
				{
						$modaUser = $tableModaUser->getUserByUnamePass($userName);
						if($modaUser!='')
						{
							FI_updatemodaext($modaUser['user_id'],'',0,2);
							return true;
						}
						else
							return false;
				}
		}
}


function FI_GetIP(){
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


function FI_gethuoyuedufensishu($user_id)
{
		  $Table_ModaExt = FLEA::getSingleton('Table_ModaExt');
		  $userext = $Table_ModaExt->find("user_id = ".$user_id);
		  $userext['huoyuedupoint'] = (float)$userext['huoyuedu']/700 * 200;
		  if($userext['huoyuedupoint']<20)
			  $userext['huoyuedupoint'] = 20;
		  $userext['fensishupoint'] = (float)$userext['fensishu']/40 * 200;
		  if($userext['fensishupoint']<20)
			  $userext['fensishupoint'] = 20;
		  $userext['zongshu'] = $userext['huoyuedu']+$userext['fensishu'];
		  return $userext;

}

function FI_getmodaclubright($clubId,$status = 0)	
{	
		$tableModaClub = FLEA::getSingleton('Table_ModaClub');
		$clubDt = $tableModaClub->getDetailByClubId($clubId);
		if(!$clubDt)
			return false;
		
		if(FI_CheckAdminRoot() && $status)
			return true;
			
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');			
		$clubCC = $tableModaClubCall->getAdminCallByClubId($clubId);
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		if($userName != $clubCC['uname'])	
			return false;
		else
			return true;

}


function FI_DEBUG($data,$stop)	
{	
		  if($_SESSION["Zend_Auth"]['storage']->username =='aaa')
		  {
			  echo "DEBUG_ENTER<br>";
			  dump($data);
			  echo "DEBUG_RETURN<br>";
			  if($stop)
			  		exit;
		  }
}




?>