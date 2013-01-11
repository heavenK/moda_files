<?php

include 'Fun_Include.php';

FLEA::loadClass('Controller_BoBase');

class Controller_ShowIndex extends Controller_BoBase
{
	
function Controller_ShowIndex()
{
//			if(time() < strtotime('2012-04-21 09:30:00'))
//			{
//				if(!FI_CheckAdminRoot())
//				{
//					if($_COOKIE['moda_version'] != 'version_1'){
//						setcookie('moda_version','version_1',time()+3600*24 *365);
//						setcookie('moda_temp','version_1',time()+3600*24 *365);
//						FI_changePage('/');
//					}
//				}
//			}
//			else
//			{
//				if($_COOKIE['moda_version'] && $_COOKIE['moda_temp']){
//					setcookie('moda_temp','version_1',time()-3600*24 *365);
//					setcookie('moda_version','version_2',time()+3600*24 *365);
//					FI_changePage('/');
//				}
//			}
//			if(!$_COOKIE['moda_version']){
//				setcookie('moda_version','version_2',time()+3600*24 *365);
//				FI_changePage('/');
//			}
			FI_get_session_by_cookie();
}
	
function actionIndex()
{
		if(FI_CheckAdminRoot())
		{
				if($this->_isPost())
				{
						$userD['username'] = $_POST['username'];
						$userD['truename'] = $this->_convertSpecialChars($_POST['truename']);
						
						$tableModaUser = FLEA::getSingleton('Table_ModaUser');
						$user_id = $tableModaUser->AdminInsertUser($userD);
						if(!$user_id)
							FI_alert("这个用户不存在,或已经是MODA会员","?Controller=ModaIndex");
						else
							FI_changePage("?Controller=ModaIndex&action=GeRenZiLiao&id=".$user_id."");
				}
				include(APP_DIR . '/showView/AdminRegister.html');	
		}
		else
				FI_alert("出错啦","?Controller=ModaIndex");		
			setcookie('moda_version','version_2',time()+3600*24 *365);
			var_dump($_COOKIE);
}
	
function actionModaHelp()
{
	//include(APP_DIR . '/showView/mdbz.html');	
	//版本切换-by zh
	if($_COOKIE['moda_version'] == 'version_2')
		include(APP_DIR."/".$_COOKIE['moda_version']."/mdbz.html");
	elseif($_COOKIE['moda_version'] == 'version_1')
		include(APP_DIR."/showView/mdbz.html");
	else
		include(APP_DIR."/version_2/mdbz.html");
}
	
function actionModaShows()
{
		$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$userDat = $tableModaUser->getUserByUname($curUsername);
	
		if($_GET["sort"])
			$sort = (int)$_GET['sort'];
		else
			$sort = 1;
	
		$tableModaShow = FLEA::getSingleton('Table_ModaShows');		
		$pagesize	=	36;
		$conditions	=	"available = 1 and public = 1";
		if($sort == 1)
			$sortby		=	"show_id DESC";
		if($sort == 2)
			$sortby		=	"views DESC";
		if($sort == 3)
			$sortby		=	"discuss_count DESC";	
				
		FLEA::loadClass('Lib_Pager');
		if($_COOKIE['moda_version'] == 'version_2'){
			FLEA::loadClass('Lib_ModaPager2');
			$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );	
		}
		elseif($_COOKIE['moda_version'] == 'version_1'){
			FLEA::loadClass('Lib_ModaPager');
			$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
		}
		else{
			FLEA::loadClass('Lib_ModaPager2');
			$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
		}
		
		
		$rowset	=	$page->rowset;
	
		$temp = 0;
		foreach($rowset as $dat)
		{
				$nickname = $tableModaUser->getUserByUserId($dat['user_id']);
				$rowset[$temp]['nickname'] = $nickname['nickname'];	
				$temp ++;
		}
		//include(APP_DIR . '/showView/mdzs.html');
		//moda 版本切换
		if($_COOKIE['moda_version'] == 'version_2')
			include(APP_DIR."/".$_COOKIE['moda_version']."/mdzs.html");
		elseif($_COOKIE['moda_version'] == 'version_1')
			include(APP_DIR."/showView/mdzs.html");
		else
			include(APP_DIR."/version_2/mdzs.html");
}
	
function actionShowPosts()
{
		$DataSource = $this->_HeaderCommonRead();
		$result = $DataSource['result'];
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		$visitUser_id = (int)$_GET['user_id'];
		
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$showData = $tableModaShows->getShowByUserName($userName);
		$shows = $showData;
		
		$mainshow = $tableModaShows->getMainShowByReallId($visitUser_id);
		$tableModaShow = FLEA::getSingleton('Table_ModaShows');		
		$pagesize	=	11;
		
		if(FI_CheckAdminRoot() || $this->_getUserAmin($visitUser_id))
			$conditions	=	"user_id = '".$visitUser_id."'and available = 1 and main = 0";
		else
			$conditions	=	"user_id = '".$visitUser_id."'and available = 1 and public = 1 and main = 0";
		$sortby		=	"created_on DESC";
		//FLEA::loadClass('Lib_ModaPager');
		//$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
		//	分页版本替换-by zh
		if($_COOKIE['moda_version'] == 'version_2'){
			FLEA::loadClass('Lib_ModaPager2');
			$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
		}
		elseif($_COOKIE['moda_version'] == 'version_1'){
			FLEA::loadClass('Lib_ModaPager');
			$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
		}
		else{
			FLEA::loadClass('Lib_ModaPager2');
			$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
		}
		$rowset	=	$page->rowset;	
		
		//include(APP_DIR . '/showView/ShowPosts.html');
		//版本切换
		if($_COOKIE['moda_version'] == 'version_2')
			include(APP_DIR."/".$_COOKIE['moda_version']."/ShowPosts.html");
		elseif($_COOKIE['moda_version'] == 'version_1')
			include(APP_DIR."/showView/ShowPosts.html");
		else
			include(APP_DIR."/version_2/ShowPosts.html");

}
	
function actionShowFaceEidt()
{
		$visitUser_id = (int)$_GET['user_id'];
		$DataSource = $this->_HeaderAdminRead();
		$result = $DataSource['result'];
		$tablemodauser = FLEA::getSingleton('Table_ModaUser');
		$tablemodashow = FLEA::getSingleton('Table_ModaShows');
		$showresult = $tablemodashow->find("show_id = '".(int)$_GET['show_id']."'");
		$result = $tablemodauser->find("user_id = '".$showresult['user_id']."'");
		if(!FI_CheckAdminRoot())
					$this->_checkUserAmin();
		$show_id = (int)$_GET['show_id'];
		$this->_CheckShowTypeA($show_id);	
		
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$showData = $tableModaShows->getShowByShowId($show_id);
		
		//include(APP_DIR . '/showView/ShowFaceEidt.html');
		//moda 版本切换
		if($_COOKIE['moda_version'] == 'version_2')
			include(APP_DIR."/".$_COOKIE['moda_version']."/ShowFaceEidt.html");
		elseif($_COOKIE['moda_version'] == 'version_1')
			include(APP_DIR."/showView/ShowFaceEidt.html");
		else
			include(APP_DIR."/version_2/ShowFaceEidt.html");	
}
	
function actionShowsEidt()
{
		$DataSource = $this->_HeaderAdminRead();
		$tablemodauser = FLEA::getSingleton('Table_ModaUser');
		$tablemodashow = FLEA::getSingleton('Table_ModaShows');
		if(FI_CheckAdminRoot())
		{ 
				$visitUser_id = $_SESSION["moda_temp_uid"]->temp_id;
				if((int)$_GET['user_id'])
						$visitUser_id =  (int)$_GET['user_id'];
		}
		else
			$visitUser_id = $this->_GetSessionUserId();	
		if($_GET["show_id"])
		{
				$show_id = $_GET['show_id'];
				$show_id = filter_var($show_id, FILTER_SANITIZE_NUMBER_INT);
		}
		$showresult = $tablemodashow->find("show_id = '".(int)$_GET['show_id']."'");
		$result = $tablemodauser->find("user_id = '".$showresult['user_id']."'");
		if($this->_isPost())
		{
				$show_id = (int)$_POST['show_id'];
				$this->_CheckShowTypeA($show_id);
				
				$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
				$AttachData = $tableModaAttach->find("attach_id = ".(int)$_POST['attach_id']);
				$atc_type = (int)$_POST['atc_type'];
				if($atc_type == 1)
				{
						$tmpData = $_POST['textShow'];
						$AttachData['content'] = $tmpData;
				}
				if($atc_type == 2)
				{
						$tmpData = $this->_convertSpecialChars($_POST['ImgContent']);
						$AttachData['content'] = $tmpData;
						$dst_w = 900;
						$dst_h = 1400;
						$urllink = "?Controller=ShowIndex&action=showsEidt&user_id=".$visitUser_id."&show_id=".$show_id;
						$upload_name = $_POST['upload_name'];
						$pathname = 'showAttachUpload/'."show_img".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
						$uploadfile = FI_ImgUpLoad($upload_name,$pathname,$dst_w,$dst_h,$urllink,1,0);
						//$uploadfile = FI_directUpLoad($upload_name,$pathname,$urllink,1);
						
						if($AttachData['img_url'])
								unlink($AttachData['img_url']);
						$AttachData['img_url'] = $uploadfile ;	
				}
				if($atc_type == 3)
				{
						$tmpVi = $this->_convertSpecialChars($_POST['mediaContent']);
						$AttachData['img_url'] = $tmpVi;
						//$tmpData = $this->_convertSpecialChars($_POST['someword']);
						$AttachData['content'] = $_POST['someword'];
				}
				$suc = $tableModaAttach->update($AttachData);//存储
				FI_changePage("?Controller=ShowIndex&action=ShowsEidt&user_id=".$visitUser_id."&show_id=".$AttachData['show_id']."");//跳转
				exit();
				
		}
		
		$this->_CheckShowTypeA($show_id);
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$showData = $tableModaShows->getShowByShowId($show_id);
		$shows = $showData;
		$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
		$AttachmentsData = $tableModaAttach->getAttachAllByShowId($show_id);
		$t = 0;//变量
		while($AttachmentsData[$t]['content']!="")
		{
				$str = $AttachmentsData[$t]['content'];
				$AttachmentsData[$t]['content'] = stripslashes($str);//去壳
				$t+=1;
		}
		if(FI_CheckAdminRoot())
				$_SESSION["moda_temp_uid"]->temp_id = $showresult['user_id'];
		//include(APP_DIR . '/showView/ShowsEidt.html');	
		//include(APP_DIR . '/tow_view/show_edit.html');	
		//版本切换-by zh
		if($_COOKIE['moda_version'] == 'version_2')
			include(APP_DIR."/".$_COOKIE['moda_version']."/show_edit.html");
		elseif($_COOKIE['moda_version'] == 'version_1')
			include(APP_DIR."/tow_view/show_edit.html");
		else
			include(APP_DIR."/version_2/show_edit.html");
}
	
function actionShowDelete()
{
		if(FI_CheckAdminRoot())
		{ 
			$visitUser_id = (int)$_GET['user_id'];
			if(!$visitUser_id)
				$this->_alert("非法操作","?Controller=ModaIndex");
		}
		else
			$visitUser_id = $this->_GetSessionUserId();
		
		$show_id = (int)$_GET['show_id'];
		$showd = $this->_CheckShowTypeA($show_id);
		$tableModaShow = FLEA::getSingleton('Table_ModaShows');
		if(FI_CheckAdminRoot())
				$rownum = $tableModaShow->deleteByShowId($show_id);
		else
		{
				if($showd['main'] == 1)
					$this->_alert("此展示不允许选手自行删除，请联系工作人员","?Controller=ModaIndex");
				else
					$rownum = $tableModaShow->deleteByShowId($show_id);
		}
		
		$huoyuedu = $rownum*(-5);
		FI_updatemodaext($showd['user_id'],'',$huoyuedu);
		
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$result = $tableModaUser->find("user_id='".$showd['user_id']."'");
		$result['showcount'] = $result['showcount'] -1 ;
		$tableModaUser->update($result);
		
		FI_changePage("?Controller=ShowIndex&action=ShowPosts&user_id=".$visitUser_id."");
	
}
	
function actionAttachDelete()
{
		$show_id = (int)$_GET['show_id'];
		if(!$show_id)
			FI_alert("出错啦","?Controller=ModaIndex");
		if(FI_CheckAdminRoot())
			$visitUser_id = (int)$_GET['user_id'];
		else
			$visitUser_id = $this->_GetSessionUserId();	
		
		$tableModaShow = FLEA::getSingleton('Table_ModaShows');
		$showdat = $tableModaShow->find("show_id = ".$show_id);
		if(!$showdat)
			FI_alert("出错啦","?Controller=ModaIndex");
		if($showdat['main'] == 1)
		{
			if(!FI_CheckAdminRoot())
			{
					FI_alert('不能删除',"/");
			}
		}
		$attach_id = (int)$_GET['attach_id'];
		$this->_CheckAttachTypeB($attach_id);
		$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
		$tableModaAttach->deleteByAttachId((int)$_GET['attach_id']);
		
		FI_updatemodaext($visitUser_id,'',-5);
		FI_changePage("?Controller=ShowIndex&action=ShowsEidt&user_id=".$visitUser_id."&show_id=".$show_id."");
}

function actionManageShows()
{
		$DataSource = $this->_HeaderAdminRead();
		$result = $DataSource['result'];
		if(FI_CheckAdminRoot())
		{
			$user_id = (int)$_GET["user_id"];
			if(!$user_id)
				$this->_alert("非法操作!","?Controller=ModaIndex");	
	
			$visitUser_id = (int)$_GET["user_id"];
		
		}
		else
		{
		
			$this->_checkUserAmin();
			$userName = $_SESSION["Zend_Auth"]['storage']->username;
			$visitUser_id = $this->_GetSessionUserId();
		}
		if((int)$_GET["show_id"])//公开动作
		{
				$show_id = (int)$_GET["show_id"];
				$this->_CheckShowTypeA($show_id);		
				$data['show_id']	= (int)$_GET['show_id'];
				$data['public']	= (int)$_GET['do'];
				if($_GET['do']!=0 and  $_GET['do']!=1 )
					$this->_alert("非法操作!!","?Controller=ModaIndex");	
	
				$tableModaShows = FLEA::getSingleton('Table_ModaShows');
				$showData = $tableModaShows->updatetar($data);
				FI_changePage("?Controller=ShowIndex&action=ShowDetail&user_id=".$visitUser_id."&show_id=".$data['show_id']."");
				
		}
		else
			FI_alert("非法操作!!!","?Controller=ModaIndex");	

}

function actionCreateShowFace()
{
			if($this->_isPost())
			{
					if((int)$_POST['visitUser_id'] == '')
							$temu = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,"",1,1);		
					else
							$temu = FI_TestSysUser("",(int)$_POST['visitUser_id'],1,1);		
					$visitUser_id = (int)$_POST['visitUser_id'];
					$userName = $temu['username'];
						
					$tableModaShows = FLEA::getSingleton('Table_ModaShows');
					if($_POST['title'] == '')
							FI_alert("请输入标题","/");
					else
					{
							$lenMax = 20;
							$len = strlen($_POST['title']);
							if($len<$lenMax||!$len) 
								  ;
							else
								 $_POST['title'] = substr($_POST['title'],0,$lenMax); //截取字符串	
							$tmpData = $this->_convertSpecialChars($_POST['title']);//加壳	 
							$showsData['title'] = $tmpData ;
							
							//$showsData['title'] = $_POST['title'] ;
							
							
					}
					$showsData['text']	=	Fi_ConvertChars($_POST['text']);
					if((int)$_POST['show_id'])
					{
							$showsData['show_id'] = (int)$_POST['show_id'];
							$showt = FI_ShowAdimnRoot((int)$_POST['show_id'],1);
							$show_id = (int)$_POST['show_id'];
							$suc = $tableModaShows->update($showsData);//返回最后一条数据ID
							FI_changePage("?Controller=ShowIndex&action=ShowsEidt&user_id=".$visitUser_id."&show_id=".$show_id."");
					}
					else
					{
							$dataShow = $tableModaShows->getTempShowByUserName($userName);
							if($dataShow)
								$tableModaShows->deleteByShowId($dataShow['show_id']);
							else
								FI_updatemodaext($visitUser_id,'',1);
								
							$showsData['user_id']	=	$visitUser_id;
							$showsData['username']	=	$userName;
							$showsData['time']	=	time();
							
	//						$showsData['text']	=	$this->_convertSpecialChars($_POST['content']);//加壳简介
							$show_id = $tableModaShows->create($showsData);//返回最后一条数据ID
							FI_changePage("?Controller=ShowIndex&action=PrePicUpload&user_id=".$visitUser_id."&show_id=".$show_id."");
					}
					
					
					
					
			}	
			else
			{
					if((int)$_GET['user_id']!='')
					{
						$result = FI_TestSysUser("",(int)$_GET['user_id'],1,1);		
						$visitUser_id = (int)$_GET['user_id'];
					}
					else
					{
							$result = FI_TestSysUser($_SESSION["Zend_Auth"]['storage']->username,"",1,1);
							$visitUser_id = $result['user_id'];
					}
					//include(APP_DIR . '/showView/fbzs.html');	
					//moda 版本切换
					if($_COOKIE['moda_version'] == 'version_2')
						include(APP_DIR."/".$_COOKIE['moda_version']."/fbzs.html");
					elseif($_COOKIE['moda_version'] == 'version_1')
						include(APP_DIR."/showView/fbzs.html");
					else
						include(APP_DIR."/version_2/fbzs.html");
			}	
}
	
function actionInsertModule()
{
	
			$show_id = (int)$_POST['show_id'];
			$this->_CheckShowTypeA($show_id);	
		
			if(FI_CheckAdminRoot())
			{ 
					$visitUser_id = $_SESSION["moda_temp_uid"]->temp_id;
					if($_GET['user_id'])
							$visitUser_id = (int)$_GET['user_id'];
			}
			else
					$visitUser_id = $this->_GetSessionUserId();
			if($this->_isPost())
			{
					$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');	
					$AttachData['atc_type'] = (int)$_POST['type_mark'];
					$atc_type = (int)$AttachData['atc_type'];
	
					if($atc_type == 1)
					{
							$AttachData['atc_type'] = $atc_type;
							$AttachData['content'] = $this->_convertSpecialChars($_POST['editor']);
					}
					if($atc_type == 2)
					{
							$urllink = "?Controller=ShowIndex&action=showsEidt&user_id=".$visitUser_id."&show_id=".$show_id;
							$upload_name = 'upload';
							$pathname = 'showAttachUpload/'."show_img".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
							$dst_w = 900;
							$dst_h = 1400;
							$uploadfile = FI_ImgUpLoad($upload_name,$pathname,$dst_w,$dst_h,$urllink,1,0);
							$AttachData['img_url'] = $uploadfile;
							$AttachData['atc_type'] = $atc_type;
							$AttachData['content'] = $this->_convertSpecialChars($_POST['ImgContent']);
							$AttachData['uploadfilename'] = 'upload';
					}
					if($atc_type == 3)
					{
							$AttachData['atc_type'] = $atc_type;
							$AttachData['img_url'] = $this->_convertSpecialChars($_POST['mediaContent']);
							$AttachData['content'] = $_POST['someword'];
					}
					$AttachData['show_id'] = $show_id;
					$id = $tableModaAttach->save($AttachData);//存储
					
					FI_updatemodaext($visitUser_id,'',5);
			}
	
			FI_changePage("?Controller=ShowIndex&action=ShowsEidt&user_id=".$visitUser_id."&show_id=".$AttachData['show_id']."");
	
}
	
	
function actionShowDetail()
{
		$DataSource = $this->_HeaderCommonRead();
		$result = $DataSource['result'];
		if($_GET['show_id'])
			$show_id = (int)$_GET['show_id'];
		if($_GET['user_id'])
			$visitUser_id = (int)$_GET['user_id'];
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		
		if($_GET['set'] == 1)
		{
				$sd = $tableModaShows->find("user_id = ".$visitUser_id." and show_id > ".$show_id." and public = 1 and available = 1  order by show_id asc ");
				if(!$sd)
						$sd = $tableModaShows->find("user_id = ".$visitUser_id." and public = 1 and available = 1  order by show_id asc ");
		}
		elseif($_GET['set'] == 2)
		{
				$sd = $tableModaShows->find("user_id = ".$visitUser_id." and show_id < ".$show_id." and public = 1 and available = 1 order by show_id desc");
				if(!$sd)
						$sd = $tableModaShows->find("user_id = ".$visitUser_id." and public = 1 and available = 1  order by show_id desc ");
										
		}
		if($sd)
				FI_changePage("?Controller=ShowIndex&action=ShowDetail&user_id=".$visitUser_id."&show_id=".$sd['show_id']);
		else	
				$showDE = $tableModaShows->getShowByShowId($show_id);
		if(!$showDE)
			FI_alert("非法连接！","?Controller=ModaIndex");
		$visitUser_id = (int)$_GET['user_id'];//访问用户ID
		if(!$visitUser_id)
			FI_alert("非法连接","?Controller=ModaIndex");
		
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$showData = $tableModaShows->getShowByShowId($show_id);
		$shows = $showData;
	
		$AttachmentsData	=	array();//附件表
		$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
		$AttachmentsData = $tableModaAttach->getAttachAllByShowId($show_id);
		
		$row = $tableModaShows->getShowByShowId($show_id);
		$row['views']+=3;
		$tableModaShows->updatetar($row);
		
		if($showDE['main'] == 1)
			//include(APP_DIR . '/tow_view/Show_official.html');
			//版本切换-by zh
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/Show_official.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/Show_official.html");
			else
				include(APP_DIR."/version_2/Show_official.html");
		else
			//include(APP_DIR . '/tow_view/show_personal.html');
			//版本切换-by zh
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/show_personal.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/show_personal.html");
			else
				include(APP_DIR."/version_2/show_personal.html");

}


function _changePage($url="",$target="self")
{
	if($url=="")
		$url	=	$_SERVER['HTTP_REFERER'];
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script type=\"text/javascript\" >$target.location='$url';</script>
</head><body></body></html>";
}

function _alertLocal($word="")
{
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');</script>
</head><body></body></html>";
}
	

function _checkUserAmin($visitUser_id = '')
{
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		if($userName=='')
			FI_alert("请登录美达后再进行操作！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));
		else
		{	
				if(FI_CheckAdminRoot())
					return true;
			
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				if($visitUser_id)
				{
					$visitUser = $tableModaUser->getUserByUserId($visitUser_id);
					if($userName == $visitUser['username'])
						return true;
					else
							FI_alert("非法操作！","?Controller=ModaIndex");
				}
				else
				{
					$visitUser = $tableModaUser->getUserByUnamePass($userName);
					if($visitUser!='')
						return true;
					else
						FI_alert("非法操作！您不是美达用户");
				}
		}
}
	
function _getUserAmin($user_id = '')
{
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		if($userName=='')
			return false;
		else
		{	
			$tableModaUser = FLEA::getSingleton('Table_ModaUser');
			if($user_id)
			{
//				$ShowUser = $tableModaUser->getUserByUserId($user_id);
				$ShowUser2 = $tableModaUser->getUserByUname($userName);
				if($ShowUser2['user_id'] == $user_id)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else//检测是否美达用户
			{
				if(FI_CheckAdminRoot())
					return true;
				$modaUser = $tableModaUser->getUserByUnamePass($userName);
				if($modaUser!='')
					return true;
				else
					return false;
			}
		}
}	
	
function _convertSpecialChars($str)
{
		if(empty($str)) return;
		if($str=="") return $str;
		  $str=str_replace("\\","\\\\",$str);//首先过滤
		  $str=trim($str);
		  $str=str_replace(">","\>",$str);
		  $str=str_replace("<","\<",$str);
		  $str=str_replace("~","\~",$str);
		  $str=str_replace("`","\`",$str);
		  $str=str_replace("!","\!",$str);
		  $str=str_replace("@","\@",$str);
		  $str=str_replace("#","\#",$str);
		  $str=str_replace("$","\$",$str);
		  $str=str_replace("%","\%",$str);
		  $str=str_replace("^","\^",$str);
		  $str=str_replace("&","\&",$str);
		  $str=str_replace("*","\*",$str);
		  $str=str_replace("(","\(",$str);
		  $str=str_replace(")","\)",$str);
		  $str=str_replace("-","\-",$str);
		  $str=str_replace("_","\_",$str);
		  $str=str_replace("+","\+",$str);
		  $str=str_replace("=","\=",$str);
		  $str=str_replace("|","\|",$str);
		  $str=str_replace("?","\?",$str);
		  $str=str_replace("/","\/",$str);
		  $str=str_replace(";","\;",$str);
		  $str=str_replace(":","\:",$str);
		  //$str=str_replace("<?"," ",$str);
		   // $str = $this->_VideoCover($str);
		$str = htmlspecialchars($str);
		return $str;
}

	function actionShowEventDiscuss()
	{
			if($_GET['event_id'])
					$event_id = (int)$_GET['event_id'];
			else
					$event_id = (int)$_POST['show_id'];
			$this->_CheckEventById($event_id);
			if($this->_isPost())
			{
					$RecContent = $this->_convertSpecialChars($_POST['content']);
					$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
					$nickname = $_SESSION["Zend_Auth"]['storage']->nickname;
					$userName = $_SESSION["Zend_Auth"]['storage']->username;
					
					if($_POST['discuss_id']=='')//发表评论
					{
							$tableModaUser = FLEA::getSingleton('Table_ModaUser');
							$userDD = $tableModaUser->getUserByUname($userName);
							
							$uid = $this->_GetSessionUserId();
							$discussData['status'] = 1;
							
							$discussData['uid'] = $uid;
							$discussData['head_img'] = $userDD['head_img'];
							$discussData['uname'] = $userName;
							$discussData['nickname'] = $nickname;
							$discussData['show_id'] = $event_id;
							$discussData['content'] = $RecContent;
							
							$id = $tableModaShowDiscuss->savetar($discussData);
					}
					else//回复评论
					{
							$discussData['discuss_id'] = (int)$_POST['discuss_id'];
							$DISdata = $tableModaShowDiscuss->getShowDiscussByID($discussData['discuss_id']);
							
							$discussData['content'] = '&nbsp;&nbsp;&nbsp;&nbsp; '.$DISdata['content'].' <div style="width:670px; float:left;  height:0px; overflow:hidden;border:1px #8387a3 dashed; border-bottom-style:none; margin-top:20px; margin-bottom:10px;"></div><div style=" width:670px; float:left; overflow:hidden; "><span style="color:#FFFFFF;">'.$nickname.'</span>&nbsp;'.date('Y-m-d').'&nbsp;&nbsp;回复 ：</div><div style=" width:670px; float:left; overflow:hidden; ">&nbsp;&nbsp;&nbsp;&nbsp;'.$RecContent.'</div>';
							
							$id = $tableModaShowDiscuss->uploadtar($discussData);
						}
						FI_changePage("?Controller=ShowIndex&action=ShowEventDiscuss&event_id=".$event_id);
			}
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$modaEvent  = $tableModaEvent->find("event_id = ".$event_id."");

			$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
			$ShowDiscuss = $tableModaShowDiscuss->findAll("status = 1 and show_id=".$event_id." order by discuss_id desc ");

			$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
			$temp = 0;
			foreach($ShowDiscuss as $data)
			{
				$oneData = $tableCdbmembers->_getUserByUsername($data['uname']);
				$ShowDiscuss[$temp]['groupid'] = $oneData['groupid'];
				$ShowDiscuss[$temp]['cdbmem_id'] = $oneData['uid'];
				$temp += 1;
			}
			//表情设置
			$tableModaFacePage = FLEA::getSingleton('Table_ModaFacePage');
			$faceall = $tableModaFacePage->_getAll();
			foreach($faceall as $face)
			{
				$face_t[$face['name']] = '<img src= "'.$face['face_img'].'" height="50"/>';
			}

			$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
			$temp = 0;
			foreach($ShowDiscuss as $dat)
			{
					$memDd = $tableCdbmembers->_getUserByUsername($dat['uname']);
					$ShowDiscuss[$temp]['groupid'] = $memDd['groupid'];

					if($ShowDiscuss[$temp]['head_img'] == "" || $ShowDiscuss[$temp]['head_img'] =="thumb_image/temp.jpg")		
					{
							$tableCdbmemberfields = FLEA::getSingleton('Table_Cdbmemberfields');
							$cdbmfi = $tableCdbmemberfields->_getUserByUID($memDd['uid']);
							if(strstr($cdbmfi['avatar'], "http"))
									$ShowDiscuss[$temp]['head_img'] = $cdbmfi['avatar'];
							else
							{
									if($cdbmfi['avatar'])
										$ShowDiscuss[$temp]['head_img'] = "http://bbs.we54.com/".$cdbmfi['avatar'];
									else
										$ShowDiscuss[$temp]['head_img'] = "thumb_image/temp.jpg";
							}
					}		
					$pieces = explode(" ",stripslashes($dat['content']));
					foreach($pieces  as $pi)
					{
							$pieces_2 = explode(":",$pi);
							foreach($pieces_2  as $pi_2)
							{
									$ds = $pi_2;
									$pi_2 = ":".$pi_2;
									if($ds!="" && $face_t[$ds])
											$ShowDiscuss[$temp]['content'] = eregi_replace($pi_2,$face_t[$ds],stripslashes($ShowDiscuss[$temp]['content']));//替换
							}
					}
					$temp ++;
			}
			if($this->_getCommonUser())
			{
					$curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
					$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
					$tableModaUser = FLEA::getSingleton('Table_ModaUser');
					$modaud = $tableModaUser->getUserByUnamePass($curUsername);
					$chatRight = 1;
			}
			else
					$chatRight = 0;
		
			//include(APP_DIR . '/showView/ShowEventDiscuss.html');
			//版本切换
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/ShowEventDiscuss.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/showView/ShowEventDiscuss.html");
			else
				include(APP_DIR."/version_2/ShowEventDiscuss.html");	
		
	}
	
	function actionTextCode()
	{
			$code = $_POST['code'];
			$code = iconv("utf-8","gb2312",$code);
			if($code == $_SESSION['validate'])
				$msg = 1;
			else
				$msg = 0;
			echo $msg;
	}	
	
	function actionAuthCode()
	{
		$ac = FLEA::getSingleton('Lib_AuthCode');
		$ac->genImg();
	}	
	
	function actionSecurimage()
	{
			$img = FLEA::getSingleton('Lib_securimage');
			$img->multi_text_color = array(
										   new Securimage_Color("#F00"),
										   new Securimage_Color("#00F"),
										   new Securimage_Color("#F0F")
										   );
			$img->use_multi_text = true;
			$img->image_signature = 'we54.com';
			$img->image_width = 165;
			$img->image_height = 60;
			$img->perturbation = 0.95;
			$img->code_length = rand(3,4);
			$img->image_bg_color = new Securimage_Color("#ffffff");
			$img->use_transparent_text = true;
			$img->text_transparency_percentage = 45;
			$img->num_lines = 5;
			$img->text_color = new Securimage_Color("#000000");
			$img->line_color = new Securimage_Color("#cccccc");
			$img->show('backgrounds/bg3.jpg');
	}	
	
	function actionTextSecurimage()
	{
			$code = $_POST['code'];
			$code = iconv("utf-8","gb2312",$code);
			
			if($code == $_SESSION['securimage_code_value'])
				$msg = 1;
			else
				$msg = 0;
			echo $msg;
	}	
	
	function actionShowDiscuss()
	{
		FI_alert("评论功能暂时关闭！");
		
				$DataSource = $this->_HeaderCommonRead();
				$result = $DataSource['result'];
				$userName = $_SESSION["Zend_Auth"]['storage']->username;
				
				if($_GET['show_id'])
					$show_id = (int)$_GET['show_id'];
				else
						FI_alert("非法连接","?Controller=ModaIndex");
				$visitUser_id = (int)$_GET['user_id'];//给头使用
				if($this->_isPost())
				{
					
						$ac = FLEA::getSingleton('Lib_securimage');
						if(!$ac->check($_POST["validate"]))
							FI_alert("验证码错误，请重试！");
					
						if(!$this->_getCommonUser())
							FI_alert("非法操作！您可能没有登录，或已被禁言");
						$RecContent = $this->_convertSpecialChars($_POST['content']);
						$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
						$nickname = $_SESSION["Zend_Auth"]['storage']->nickname;
						
						if($_POST['discuss_id']=='')//发表评论
						{
								$tableModaUser = FLEA::getSingleton('Table_ModaUser');
								$userDD = $tableModaUser->getUserByUname($userName);
								$uid = $this->_GetSessionUserId();
								$discussData['uid'] = $uid;
								$discussData['head_img'] = $userDD['head_img'];
								$discussData['uname'] = $userName;
								$discussData['nickname'] = $nickname;
								$discussData['show_id'] = $show_id;
								$discussData['content'] = $RecContent;
								$id = $tableModaShowDiscuss->savetar($discussData);
						}
						else//回复评论
						{
								$discussData['discuss_id'] = (int)$_POST['discuss_id'];
								$discussData['show_id'] = $show_id;
								/* 留言处理
								*/
								$DISdata = $tableModaShowDiscuss->getShowDiscussByID($discussData['discuss_id']);
								$discussData['content'] = '&nbsp;&nbsp;&nbsp;&nbsp; '.$DISdata['content'].' <div style="width:670px; float:left;  height:0px; overflow:hidden;border:1px #8387a3 dashed; border-bottom-style:none; margin-top:20px; margin-bottom:10px;"></div><div style=" width:670px; float:left; overflow:hidden; "><span style="color:#FFFFFF;">'.$nickname.'</span>&nbsp;'.date('Y-m-d').'&nbsp;&nbsp;回复 ：</div><div style=" width:670px; float:left; overflow:hidden; ">&nbsp;&nbsp;&nbsp;&nbsp;'.$RecContent.'</div>';
								$id = $tableModaShowDiscuss->uploadtar($discussData);
						}
						FI_changePage("?Controller=ShowIndex&action=ShowDiscuss&user_id=".$visitUser_id."&show_id=".$show_id."");
						exit;
				}
				$tableModaShows = FLEA::getSingleton('Table_ModaShows');
				$showData = $tableModaShows->getShowByShowId($show_id);
				$shows = $showData;
				
				$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
				$pagesize	=	20;
				$conditions	=	"status = 0 and show_id=".$show_id;
				$sortby		=	"discuss_id DESC";
				FLEA::loadClass('Lib_Pager');
				FLEA::loadClass('Lib_ModaPager');
				$page	=	new Lib_ModaPager( $tableModaShowDiscuss, $pagesize, $conditions , $sortby );
				$ShowDiscuss	=	$page->rowset;

				$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
				$temp = 0;
				foreach($ShowDiscuss as $data)
				{
						$oneData = $tableCdbmembers->_getUserByUsername($data['uname']);
						$ShowDiscuss[$temp]['groupid'] = $oneData['groupid'];
						$ShowDiscuss[$temp]['cdbmem_id'] = $oneData['uid'];
						$temp += 1;
				}
				//表情设置
				$tableModaFacePage = FLEA::getSingleton('Table_ModaFacePage');
				$faceall = $tableModaFacePage->_getAll();
				foreach($faceall as $face)
						$face_t[$face['name']] = '<img src= "'.$face['face_img'].'" height="50"/>';
				$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
				$temp = 0;	
				/*禁言及头像,表情设置
				*/
				foreach($ShowDiscuss as $dat)
				{
						$memDd = $tableCdbmembers->_getUserByUsername($dat['uname']);
						$ShowDiscuss[$temp]['groupid'] = $memDd['groupid'];
						
						$tableCdbmemberfields = FLEA::getSingleton('Table_Cdbmemberfields');
						$cdbmfi = $tableCdbmemberfields->_getUserByUID($memDd['uid']);
						if(strstr($cdbmfi['avatar'], "http"))
							$ShowDiscuss[$temp]['head_img'] = $cdbmfi['avatar'];
						else
						{
							if($cdbmfi['avatar'])
								$ShowDiscuss[$temp]['head_img'] = "http://bbs.we54.com/".$cdbmfi['avatar'];
							else
								$ShowDiscuss[$temp]['head_img'] = "/thumb_image/temp.jpg";
						}
						
						$pieces = explode(" ",stripslashes($dat['content']));
						foreach($pieces  as $pi)
						{
								$pieces_2 = explode(":",$pi);
								foreach($pieces_2  as $pi_2)
								{
										$ds = $pi_2;
										$pi_2 = ":".$pi_2;
										if($ds=="")
										;
										else
										{
												if($face_t[$ds])
													$ShowDiscuss[$temp]['content'] = eregi_replace($pi_2,$face_t[$ds],stripslashes($ShowDiscuss[$temp]['content']));//替换
										}
								}
						}
						$temp ++;
				}
				$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
				if($this->_getCommonUser())
				{
						$curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
						$tableModaUser = FLEA::getSingleton('Table_ModaUser');
						$modaud = $tableModaUser->getUserByUnamePass($curUsername);
						$chatRight = 1;
				}
				else
						$chatRight = 0;
				
				include(APP_DIR . '/showView/ShowDiscuss_T.html');		
    }	
	
	
	
	
	function actionDeleteDiscuss()
	{
		
			if(FI_CheckAdminRoot())
				$visitUser_id = (int)$_GET['user_id'];
			
			$show_id = (int)$_GET['show_id'];
			$discuss_id = (int)$_GET['discuss_id'];
			
			$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
			$DiscussDE = $tableModaShowDiscuss->getShowDiscussByID($discuss_id);
			if(!$DiscussDE)
				FI_alert("错误！评论不存在","?Controller=ModaIndex");
			
			$TableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
			$sdat = $TableModaShowDiscuss->find("status = 1 and discuss_id = ".$discuss_id);
			$TableModaShowDiscuss->deleteByDiscussId($discuss_id);
			
			if(!$sdat)
			{
					$tableModaShows = FLEA::getSingleton('Table_ModaShows');
					$showData = $tableModaShows->getShowByShowId($show_id);	
					$showData['discuss_count'] -= 1;
					$tableModaShows->updatetar($showData);
					FI_changePage("?Controller=ShowIndex&action=ShowDiscuss&user_id=".$visitUser_id."&show_id=".$show_id."");
			}
			else
					FI_changePage("?Controller=ShowIndex&action=ShowEventDiscuss&event_id=".$show_id);
	
	}
	
	
	function actionAddToFriend()
	{
			$this->_checkUserAmin();
			$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
			$visitUser_id = (int)$_GET["user_id"];//目标ID
			if($visitUser_id)
			;
			else
					FI_alert("非法操作","?Controller=ModaIndex");
			$tableModaUser = FLEA::getSingleton('Table_ModaUser');	
			$ModaUser = $tableModaUser->getUserByUserId($visitUser_id);
			if($curUsername == $ModaUser['username'])
				FI_alert("自己不能加自己为好友","?controller=ModaIndex&action=Person");	exit();
		
			$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');	
			$friendData = $tableModaFriend->friendCheck($curUsername,$ModaUser['username']);
			if($friendData)
				FI_alert("审核中或已加为好友","?controller=ModaIndex&action=Person&id=".$visitUser_id."");	exit();
			
			$uid = $this->_GetSessionUserId();
			$userdata['uid'] = $uid;
			$userdata['fuid'] = $visitUser_id;
			$userdata['uname'] = $curUsername;
			$userdata['fusername'] = $ModaUser['username'];
			$userdata['status'] = 0;
			$userdata['gid'] = 0;
			$userdata['note'] = 0;
			$userdata['num'] = 0;
			$userdata['dateline'] = time();
			$tableModaFriend->addFriend($userdata);
			
			FI_changePage("?controller=ModaIndex&action=Person&id=".$visitUser_id."");
	}
	
	
	function actionModaFriend()
	{
		
		$DataSource = $this->_HeaderCommonRead();
		$result = $DataSource['result'];
		if($_GET["user_id"])
			$visitUser_id = (int)$_GET["user_id"];//页头信息
		if($_POST["user_id"])
			$visitUser_id = (int)$_POST["user_id"];//页头信息
		if(!$visitUser_id)
			FI_alert("非法连接","?Controller=ModaIndex");
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');
		if($this->_isPost())
		{
				$note = $this->_convertSpecialChars($_POST['note']);
				$Data['note'] = $note;
				$fuid = (int)$_POST['fuid'];
				$uid = $this->_GetSessionUserId();
				
				$tableModaFriend->updateByCon($uid,$fuid,$Data);
				FI_changePage("?controller=ShowIndex&action=ModaFriend&user_id=".$visitUser_id."");
		}
		
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$visitUse = $tableModaUser->getUserByUserId($visitUser_id);
		$tableModaShow = FLEA::getSingleton('Table_ModaFriend');		
		$pagesize	=	12;
		$conditions	=	"uname='".$visitUse['username']."' and status=1";
		$sortby		=	"dateline DESC";
		FLEA::loadClass('Lib_ModaPager');
		$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
		$rowset	=	$page->rowset;	
		$temp = 0;
		foreach($rowset as $data)
		{
				$oneData = $tableModaUser->getUserByUname($data['fusername']);
				if($oneData)
				{
						$rowset[$temp]['nickname'] = $oneData['nickname'];//为数据添加昵称
						$rowset[$temp]['head_img'] = $oneData['head_img'];
				}
				else
						$rowset[$temp] ="";
				$temp += 1;
		}
		
		if($this->_getUserAmin($visitUser_id))
		{
				$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');	
				$applyData = $tableModaFriend->getFriendApplyByUsername($userName,0);//待审核
		}
		
		include(APP_DIR . '/showView/modaFriend.html');	
	}



	function actionFriendDelete()
	{
			$this->_checkUserAmin();
			$user_id = (int)$_GET["user_id"];
			if($user_id)
			;
			else
					FI_alert("非法操作","?Controller=ModaIndex");
			$visitUser_id = $this->_GetSessionUserId();
			
			$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');	
			$friends = $tableModaFriend->friendDelete($visitUser_id,$user_id);
			if($friends)
				$this->_alert("成功");
			else
				$this->_alert("请确认正常操作");
			FI_changePage("?controller=ShowIndex&action=ModaFriend");
	}
	
	function actionInfoCenter()
	{
		$DataSource = $this->_HeaderAdminRead();
		$result = $DataSource['result'];
		$this->_checkUserAmin();
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		$visitUser_id = $this->_GetSessionUserId();
		
		$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');	
		$applyData = $tableModaFriend->getFriendApplyByUsername($userName,0);//待审核
		
		include(APP_DIR . '/showView/InfoCenter.html');	
	}
	
	function actionFriendDo()
	{
			$this->_checkUserAmin();
			$visitUser_id = $this->_GetSessionUserId();
			$user_id = (int)$_GET['user_id'];
			$tableModaUser = FLEA::getSingleton('Table_ModaUser');	
			$ModaU = $tableModaUser->getUserByUserId($user_id);
			if(!$ModaU)
					FI_alert("非法操作","?Controller=ModaIndex");
			
			if($_GET["do"])
					$do = (int)$_GET["do"];
			else
					FI_alert("非法操作","?Controller=ModaIndex");
			
			$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');
			if($do)	
				$tableModaFriend->friendPass($user_id,$visitUser_id);//通过
			else
				$tableModaFriend->friendIgnore($user_id,$visitUser_id);	//忽略
			
			FI_changePage("?controller=ShowIndex&action=ModaFriend&user_id=".$visitUser_id."");
	}
	
function _HeaderCommonRead()
{
		if($_GET["user_id"])
			$visitUser_id = (int)$_GET["user_id"];//被访问者
		if($_POST["user_id"])
			$visitUser_id = (int)$_POST["user_id"];//被访问者
		
		if(-1 == $visitUser_id)
			return true;
		
		if(!$visitUser_id)
			FI_alert("错误，用户名不能为空","?Controller=ModaIndex");
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$result = $tableModaUser->getUserByUserId($visitUser_id);
		
		if($result)
		{
			$DataSource['result'] = $result;
			return $DataSource;
		}
		else
				FI_alert("非法连接,此用户不存在","?Controller=ModaIndex");
}	

function _HeaderAdminRead()
{
	
		$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		if(FI_CheckAdminRoot())
		{
				if($_GET["user_id"])
					$visitUser_id = (int)$_GET["user_id"];//被访问者
				if($_POST["user_id"])
					$visitUser_id = (int)$_POST["user_id"];//被访问者
				$userDat = $tableModaUser->getUserByUserId($visitUser_id);
				if($userDat)
				{
						$DataSource['result'] = $userDat;
						return $DataSource;
				}
				else
						FI_alert("此用户不存在","?Controller=ModaIndex");
		}
		else
		{
				$userDat = $tableModaUser->getUserByUname($curUsername);
				if($userDat)
				{
						$DataSource['result'] = $userDat;
						return $DataSource;
				}
				else
						FI_alert("非法操作,请登录后操作","?Controller=ModaIndex");
		}
}	

function actionClubDown()
{
			$visitUser_id = (int)$_GET['user_id'];
			if(!$visitUser_id)
					FI_alert("非法连接","?Controller=ModaIndex");
					
			$this->_CheckUserTypeC($visitUser_id);		
			$clubId = (int)$_GET['club_id'];
			$tableModaClub = FLEA::getSingleton('Table_ModaClub');
			$clubDt = $tableModaClub->getDetailByClubId($clubId);
			if(!$clubDt)
				FI_alert("非法操作，该俱乐部不存在","?Controller=ModaIndex");	
				
			$uid = $this->_GetSessionUserId();
			if(!FI_CheckAdminRoot())
			{
				if($clubDt['uid'] != $uid)
					FI_alert("非法连接","?Controller=ModaIndex");
			}
			$tableModaClub = FLEA::getSingleton('Table_ModaClub');
			$data['club_id'] = $clubId;
			$data['status'] = 1;
			$tableModaClub->updatetar($data);
		
			FI_changePage("?Controller=ShowIndex&action=ModaClubAllList");
		
	}


function actionNewClubCall()
{
		if($this->_isPost())
		{
				$visitUser_id = (int)$_POST['chair_id'];						
				if($visitUser_id != -1)
					$this->_CheckUserTypeC($visitUser_id);	
				$this->_getCommonUser();
				$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
				$curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
				
				$clubData['creater_name'] = $curUsername;
				$clubData['creaternkname'] = $curNickname;
				$clubData['uid'] = $visitUser_id;//主席ID
				$clubData['uname'] = "unuse";
				$title = $this->_convertSpecialChars($_POST['title']);
				$clubData['title'] = $title;
				/*限制字数
				*/
				$chatitle = $this->cut_str($clubData['title'],30);
				$clubData['title'] = $chatitle;
				if(strlen($clubData['title'])<4)
							FI_alert("标题太短了","?Controller=ShowIndex&action=ModaClubAllList");
				/*数据库时间与系统时间有出入
				*/
				$nowtime = date("Y-m-d H:i:s",time());
				$clubData['dateline'] = $nowtime;
				
				/*读取moda_club
				*/
				$tableModaClub = FLEA::getSingleton('Table_ModaClub');
				$clubId = $tableModaClub->savetar($clubData);
				/*
				*/
				$clubCallData['club_id'] = $clubId;
				$clubCallData['chair_id'] = $visitUser_id;//主席ID
				$clubCallData['uname'] = $curUsername;
				$clubCallData['content'] = $_POST['content'];
				/*限制字数
				*/
				if(strlen($clubData['content'])<4)
							FI_alert("内容太少了","?Controller=ShowIndex&action=ModaClubAllList");
//				$chal = $this->cut_str($clubCallData['content'],200);
//				$clubCallData['content'] = $chal;
				
				$clubCallData['power'] = 1;
				$clubCallData['nickname'] = $curNickname;
				/*	读取moda_clubcall
				*/
				$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
				$tableModaClubCall->savetar($clubCallData);
		}
		FI_changePage("?Controller=ShowIndex&action=ModaClubAllList");
}




	function actionModaClubAllList()
	{
				$conditions	= "";
				if($this->_isPost())
						FI_changePage("?Controller=ShowIndex&action=ModaClubAllList&key=".$_POST['serch']);
				if($_GET['key'])
				{
						$tableshowips = FLEA::getSingleton('Table_ModaShowIps');
						$suc = $tableshowips->forSearch();
						if(!$suc)
								FI_alert("请勿频繁搜索！","/");
						$keyw = $this->_convertSpecialChars($_GET['key']);
						$tableModaUser = FLEA::getSingleton('Table_ModaUser');
						$res	=	"pass = 1 and truename like '%$keyw%'";			
						$userdats = $tableModaUser->findAll($res);
						$tm = 0;
						foreach($userdats as $dat)
						{
							if($tm)
								$conditions	.= " or uid = ".$dat['user_id'];
							else
								$conditions	.= "uid = ".$dat['user_id'];
							$tm++;	
						}
					
				}
				$tableModaShow = FLEA::getSingleton('Table_ModaClub');		
				$pagesize	=	41;
				$sortby		=	"dateline DESC";
				if($_COOKIE['moda_version'] == 'version_2'){
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
				}
				elseif($_COOKIE['moda_version'] == 'version_1'){
					FLEA::loadClass('Lib_ModaPager');
					$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
				}
				else{
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
				}
				$rowset	=	$page->rowset;	
				$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
				$temp = 0;			
				foreach($rowset as $dat)
				{
						$memDd = $tableCdbmembers->_getUserByUsername($dat['creater_name']);
						$rowset[$temp]['groupid'] = $memDd['groupid'];
						$tableModaUser = FLEA::getSingleton('Table_ModaUser');
						if($dat['uid'] == -1)
							$rowset[$temp]['truename'] = "贴吧综合区";
						else
						{
							$userdat = $tableModaUser->getUserByUserId($dat['uid']);
							$rowset[$temp]['truename'] = $userdat['truename'];
						}
						$temp ++;
				}
				$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
				if($this->_getCommonUser())
				{
						$curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
						$tableModaUser = FLEA::getSingleton('Table_ModaUser');
						$modaud = $tableModaUser->getUserByUnamePass($curUsername);
						$chatRight = 1;
				}
				else
						$chatRight = 0;
			
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				$modauesrs = $tableModaUser->findAll("pass = 1");
			
				//include(APP_DIR . '/showView/modaclublist.html');
				//moda 版本切换
				if($_COOKIE['moda_version'] == 'version_2')
					include(APP_DIR."/".$_COOKIE['moda_version']."/modaclublist.html");
				elseif($_COOKIE['moda_version'] == 'version_1')
					include(APP_DIR."/showView/modaclublist.html");
				else
					include(APP_DIR."/version_2/modaclublist.html");
}
	
function actionModaClub()
{
			
			$DataSource = $this->_HeaderCommonRead();
			$result = $DataSource['result'];
			if($_GET["user_id"])
				$visitUser_id = (int)$_GET["user_id"];//被访问者
			if($_POST["user_id"])
				$visitUser_id = (int)$_POST["user_id"];//被访问者
			if($_POST['highL'])
			{
					  $this->_CheckClubCallTypeE((int)$_POST['club_id']);	
					  if($this->_getUserAmin($visitUser_id) || FI_CheckAdminRoot() )
					  {
							  $color = htmlspecialchars($this->_convertSpecialChars($_POST['highL']));
							  $clubData['title'] = htmlspecialchars($this->_convertSpecialChars($_POST['title']));
							  $clubData['title'] = $clubData['title'].'^?'.$color;
							  $clubData['club_id'] = (int)$_POST['club_id'];
							  $tableModaClub = FLEA::getSingleton('Table_ModaClub');
							  $clb = $tableModaClub->getDetailByClubId((int)$_POST['club_id']);
							  $clubData['dateline'] = $clb['dateline'];
							  $tableModaClub->updatetar($clubData);
							  FI_changePage("?controller=ShowIndex&action=ModaClub&user_id=".$visitUser_id."&club_id=".(int)$_POST['club_id']."");
							  exit;
					  }
					  FI_changePage("?controller=ShowIndex&action=ModaClub&user_id=".$visitUser_id."&club_id=".(int)$_POST['club_id']."");
			}
			if($this->_isPost())
			{
					  $this->_checkEXTuser();//认证会员可发
					  if($_POST['Id'])
					  {
							  $this->_CheckClubCallTypeE((int)$_POST['club_id']);	
								  
							  $clubData['title'] = $this->_convertSpecialChars($_POST['title']);
							  $clubData['club_id'] = $_POST['club_id'];
							  $tableModaClub = FLEA::getSingleton('Table_ModaClub');
							  $tableModaClub->updatetar($clubData);
							  
							  $clubCallData['Id'] = $_POST['Id'];
							  $clubCallData['content'] = $this->_convertSpecialChars($_POST['content']);
							  $tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
							  
							  $modaclub = $tableModaClubCall->getCallById($clubData['club_id']);
							  $clubCallData['dateline'] = $modaclub['dateline'];
							  
							  $tableModaClubCall->updatetar($clubCallData);
							  FI_changePage("?controller=ShowIndex&action=ModaClubCall&user_id=".$visitUser_id."&club_id=".$_POST['club_id']."");
					  }
				  
				  
				  
					  $curUsername = $_SESSION["Zend_Auth"]['storage']->username;
					  $curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
					  $clubData['creater_name'] = $curUsername;
					  $clubData['creaternkname'] = $curNickname;
					  $clubData['uid'] = $visitUser_id;
					  $clubData['uname'] = "unuse";
					  $title = $this->_convertSpecialChars($_POST['title']);
					  $clubData['title'] = $title;
					  /*限制字数
					  */
					  $chatitle = $this->cut_str($clubData['title'],30);
					  $clubData['title'] = $chatitle;
						if(strlen($clubData['title'])<4)
									FI_alert("标题太短了","?Controller=ShowIndex&action=ModaClubCall&user_id=".$visitUser_id);
					  /*数据库时间与系统时间有出入
					  */
					  $nowtime = date("Y-m-d H:i:s",time());
					  $clubData['dateline'] = $nowtime;
					  /*读取moda_club
					  */
					  $tableModaClub = FLEA::getSingleton('Table_ModaClub');
					  $clubId = $tableModaClub->savetar($clubData);
					  $clubCallData['club_id'] = $clubId;
					  $clubCallData['chair_id'] = $visitUser_id;
					  $clubCallData['uname'] = $curUsername;
					  $clubCallData['content'] = $this->_convertSpecialChars($_POST['content']);
						if(strlen($clubCallData['content'])<4)
									FI_alert("内容太少了","?Controller=ShowIndex&action=ModaClubCall&user_id=".$visitUser_id);
					  /*限制字数
					  */
					  $chal = $this->cut_str($clubCallData['content'],200);
					  $clubCallData['content'] = $chal;
					  $clubCallData['power'] = 1;
					  $clubCallData['nickname'] = $curNickname;
					  /*	读取moda_clubcall
					  */
					  $tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
					  $tableModaClubCall->savetar($clubCallData);
	  
					  if(FI_getmodaclubright($clubId))
							  FI_updatemodaext($visitUser_id,'',1);
					  
					  FI_changePage("?controller=ShowIndex&action=ModaClubCall&user_id=".$visitUser_id."&club_id=".$clubId."");
			}
			if($visitUser_id == -1)
					FI_changePage("?Controller=ShowIndex&action=ModaClubAllList");
			$tableModaShow = FLEA::getSingleton('Table_ModaClub');		
			$pagesize	=	21;
			$conditions	=	"uid=".$visitUser_id."";
			$sortby		=	"dateline DESC";
			if($_COOKIE['moda_version'] == 'version_2'){
				FLEA::loadClass('Lib_ModaPager2');
				$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
			}
			elseif($_COOKIE['moda_version'] == 'version_1'){
				FLEA::loadClass('Lib_ModaPager');
				$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
			}
			else{
				FLEA::loadClass('Lib_ModaPager');
				$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
			}
			$rowset	=	$page->rowset;	
			$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
			$temp = 0;	
			foreach($rowset as $dat)
			{
					$memDd = $tableCdbmembers->_getUserByUsername($dat['creater_name']);
					$rowset[$temp]['groupid'] = $memDd['groupid'];
					$temp ++;
			}
			$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
			if($this->_getCommonUser())
			{
					$curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
					$tableModaUser = FLEA::getSingleton('Table_ModaUser');
					$modaud = $tableModaUser->getUserByUnamePass($curUsername);
					$chatRight = 1;
			}
			else
					$chatRight = 0;
					
			//include(APP_DIR . '/showView/ModaClub.html');	
			//moda 版本切换
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/ModaClub.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/showView/ModaClub.html");
			else
				include(APP_DIR."/version_2/ModaClub.html");

}


function actionModaClubCallEdit()
{
	
		$DataSource = $this->_HeaderCommonRead();
		$result = $DataSource['result'];
		if($_GET['user_id'])
			$visitUser_id = (int)$_GET['user_id'];
		else
			$visitUser_id = (int)$_POST['user_id'];
		if($_GET['club_id'])
			$club_id = (int)$_GET['club_id'];
		else
			$club_id = (int)$_POST['club_id'];
		if($_GET['Id'])
			$Id = (int)$_GET['Id'];
		else
			$Id = (int)$_POST['Id'];
			
		$this->_CheckByClubCallEditRight($Id);	
		
		$tableModaClub = FLEA::getSingleton('Table_ModaClub');
		$clubDt = $tableModaClub->getDetailByClubId($club_id);
		if(!$clubDt)
				FI_alert("错误，帖子不存在","?Controller=ModaIndex");
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
		$ccdat = $tableModaClubCall->getCallById($Id);
		if(!$ccdat)
				FI_alert("错误，回复不存在","?Controller=ModaIndex");
		if($this->_isPost())
		{
				$title = $this->cut_str($this->_convertSpecialChars($_POST['title']),30);
				if($title)
				{
						$ncda['club_id'] = $clubDt['club_id'];
						$ncda['dateline'] = $clubDt['dateline'];
						$ncda['title'] = $title;
						$suc = $tableModaClub->updatetar($ncda);
						if(!$suc)
								FI_alert("错误，标题保存失败","?Controller=ModaIndex");
				}
				$nccdat['Id'] = $ccdat['Id'];
				$nccdat['dateline'] = $ccdat['dateline'];
				$nccdat['content'] = $_POST['content']; 
				$suc = $tableModaClubCall->updatetar($nccdat);
				if(!$suc)
						FI_alert("错误，内容保存失败","?Controller=ModaIndex");
				else
						FI_changePage("?Controller=ShowIndex&action=ModaClubCall&user_id=".$visitUser_id."&club_id=".$club_id."");
		}
		
		
		include(APP_DIR . '/showView/ModaClubCallEdit.html');	
	
}


function actionModaClubCall()
{
	//FI_alert("贴吧功能暂时关闭！");
		$DataSource = $this->_HeaderCommonRead();
		$result = $DataSource['result'];

		if($_GET['user_id'])
			$visitUser_id = (int)$_GET['user_id'];
		else
			$visitUser_id = (int)$_POST['user_id'];
		
		if($_GET['club_id'])	
			$clubId = (int)$_GET['club_id'];
		else
			$clubId = (int)$_POST['club_id'];
		
		$tableModaClub = FLEA::getSingleton('Table_ModaClub');
		$clubDt = $tableModaClub->getDetailByClubId($clubId);
		if(!$clubDt)
				FI_alert("错误，贴吧不存在","?Controller=ModaIndex");
		
		if($this->_isPost())
		{
				if($clubDt['status'] == 1)
					FI_alert("错误，此主题回复已关闭","?Controller=ModaIndex");
				if(!$this->_getCommonUser())
					FI_alert("非法操作！您可能没有登录，或已被禁言","?Controller=ModaIndex");
			
				$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
				$curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
				
				$clubCallData['club_id'] = $clubId;
				$clubCallData['chair_id'] = $visitUser_id;
				
				$clubCallData['uname'] = $curUsername;
				$clubCallData['power'] = 0;
				$clubCallData['nickname'] = $curNickname;
				
				if($_POST['call_id'])
				{
						$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
						$ccdat = $tableModaClubCall->getCallById((int)$_POST['call_id']);
						if(!$ccdat)
							FI_alert("错误，评论不存在","?Controller=ModaIndex");
						$tempNicname = $this->_convertSpecialChars($_POST['call_nickname']);
						$tempCon = $ccdat['content'];
						$call_content ='回复&nbsp;&nbsp;'.$tempNicname.'&nbsp;&nbsp;的留言:'.$tempCon;
						$clubCallData['call_content'] = $call_content;
				}
				
				$clubCallData['call_id'] = (int)$_POST['call_id'];
				$clubCallData['content'] = $_POST['content'];
				$clubCallData['content'] = str_replace("rgb","'rgb'",$_POST['content']);
				
				$clubCallData['content'] = $clubCallData['content'];
				$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
				$suc = $tableModaClubCall->savetar($clubCallData);
				if(!$suc)
						FI_alert("发生错误","?Controller=ModaIndex");
				
				$clubDt['call_num']+=1;
				$clubDpu['club_id'] = $clubDt['club_id'];
				$clubDpu['call_num'] = $clubDt['call_num'];
				$nowtime = date("Y-m-d H:i:s",time());
				$clubDpu['dateline'] = $nowtime;
				$tableModaClub->updatetar($clubDpu);
			
				if(FI_getmodaclubright($clubDt['club_id']))
						FI_updatemodaext($visitUser_id,'',1);
			
				FI_changePage("?controller=ShowIndex&action=ModaClubCall&user_id=".$visitUser_id."&club_id=".$clubId."");
		}
			//IP限制
			/*$table = FLEA::getSingleton('Table_ModaShowIps');	
			if(!$table->checkClubDatelineIp($clubDt['club_id'])){
				//$this->_alert("一个IP一天只能投1次！");exit();
			}
			else
			{*/
				$clubDt['views']+=3;
				$tableModaClub->updatetar($clubDt);
			//}
			$title = $clubDt['title'];
			$pieces = explode("^?",stripslashes($title)); 
			$title = $pieces[0];
			
			$tableModaShow = FLEA::getSingleton('Table_ModaClubCall');		
			$pagesize	=	15;
			$conditions	=	"club_id = '".$clubId."'";
			$sortby		=	"dateline ASC";
			if($_COOKIE['moda_version'] == 'version_2'){
				FLEA::loadClass('Lib_ModaPager2');
				$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
			}
			elseif($_COOKIE['moda_version'] == 'version_1'){
				FLEA::loadClass('Lib_ModaPager');
				$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
			}
			else{
				FLEA::loadClass('Lib_ModaPager2');
				$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
			}
			$rowset	=	$page->rowset;	
			
			$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
			$temp = 0;
			foreach($rowset as $data)
			{
				$oneData = $tableCdbmembers->_getUserByUsername($data['uname']);
				$rowset[$temp]['groupid'] = $oneData['groupid'];
				$rowset[$temp]['cdbmem_id'] = $oneData['uid'];
				$temp += 1;
		
			}
		
			/*当前用户会话
			*/
			$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
			if($this->_getCommonUser())
			{
				$curNickname = $_SESSION["Zend_Auth"]['storage']->nickname;
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				$modaud = $tableModaUser->getUserByUnamePass($curUsername);
				if($clubDt['status'] != 1)
					$chatRight = 1;
			}
			else
				$chatRight = 0;
			 
			//include(APP_DIR . '/showView/ModaClubCall.html');
			//moda 版本切换
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/ModaClubCall.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/showView/ModaClubCall.html");
			else
				include(APP_DIR."/version_2/ModaClubCall.html");
}

function actionClubCallDelete()
{
		$visitUser_id = $_GET['user_id'];
		$clubId = (int)$_GET['club_id'];
		
		$this->_CheckClubCallTypeE($clubId);
		$Id = (int)$_GET['Id'];
		if(!$Id)
			FI_alert("非法操作","?Controller=ModaIndex");
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
		$clubDt = $tableModaClubCall->getCallById($Id);
		if(!$clubDt)
				FI_alert("非法连接","?Controller=ModaIndex");
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
		$tableModaClubCall->deleteById($Id);
				
		FI_changePage("?controller=ShowIndex&action=ModaClubCall&user_id=".$visitUser_id."&club_id=".$clubId."");
	
}

function actionClubDelete()
{
		$visitUser_id = (int)$_GET['user_id'];
		if(!$visitUser_id)
			FI_alert("非法连接","?Controller=ModaIndex");

		if($visitUser_id != -1)
		$this->_CheckUserTypeC($visitUser_id);		
		$clubId = (int)$_GET['club_id'];
		
		$tableModaClub = FLEA::getSingleton('Table_ModaClub');
		$clubDt = $tableModaClub->getDetailByClubId($clubId);
		if(!$clubDt)
			FI_alert("非法操作，该俱乐部不存在","?Controller=ModaIndex");	
		
		$uid = $this->_GetSessionUserId();
		if(!FI_CheckAdminRoot())
		{
			if($clubDt['uid'] != $uid)
				FI_alert("非法操作,您无权操作","?Controller=ModaIndex");	
		}
		if(1==$_GET['do'])
		{
				$tableModaClub = FLEA::getSingleton('Table_ModaClub');
				$dat = $tableModaClub->getDetailByClubId($clubId);
				$data['dateline'] = $dat['dateline'];
				$data['club_id'] = $clubId;
				$data['status'] = 1;
				$tableModaClub->updatetar($data);
			
				FI_changePage("?Controller=ShowIndex&action=ModaClubAllList");
			
			}
		else
		if(2==$_GET['do'])
		{
				$tableModaClub = FLEA::getSingleton('Table_ModaClub');
				$dat = $tableModaClub->getDetailByClubId($clubId);
				$data['dateline'] = $dat['dateline'];
				$data['club_id'] = $clubId;
				$data['status'] = 0;
				$tableModaClub->updatetar($data);
			
				FI_changePage("?Controller=ShowIndex&action=ModaClubAllList");
		}
		else
		{
				$tableModaClub = FLEA::getSingleton('Table_ModaClub');
				$tableModaClub->deleteByClubId($clubId);
				FI_changePage("?controller=ShowIndex&action=ModaClubAllList");
		}
	
}
	
function _GetSessionUserId()
{
		$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
		
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$userDat = $tableModaUser->getUserByUname($curUsername);
		
		return $userDat['user_id'];
}

function _CheckShowTypeA($show_id)
{
		//$userName = $_SESSION["Zend_Auth"]['storage']->username;
		$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
		$userda = $Table_ModaUser->find("username = '".$_SESSION["Zend_Auth"]['storage']->username."'");
		
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$showDE = $tableModaShows->getShowByShowId($show_id);
		if(!$showDE)
			FI_alert("非法连接！该展示不存在","?Controller=ModaIndex");
		else
		{
				if(FI_CheckAdminRoot())
						return $showDE;
				else
				{
						if($showDE['user_id'] == $userda['user_id'])
							return $showDE;
						else
							FI_alert("非法操作！您无权进行操作","?Controller=ModaIndex");
				}	
		}
}	
	

function _CheckAttachTypeB($attach_id)
{
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		$tableModaAttachments = FLEA::getSingleton('Table_ModaAttachments');
		$AttachDE = $tableModaAttachments->getAttachByAttachId($attach_id);
		if(!$AttachDE)
			FI_alert("非法连接！","?Controller=ModaIndex");
		$this->_CheckShowTypeA($AttachDE['show_id']);
}


function _CheckDiscussTypeM($discuss_id)
{
		$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
		$DiscussDE = $tableModaShowDiscuss->getShowDiscussByID($discuss_id);
		if(!$DiscussDE)
			FI_alert("错误！评论不存在","?Controller=ModaIndex");
		$this->_CheckShowTypeA($DiscussDE['show_id']);
}

function _CheckUserTypeC($visitUser_id)
{
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$userD = $tableModaUser->getUserByUserId($visitUser_id);
		if(!$userD)
			FI_alert("错误，此用户不存在","?Controller=ModaIndex");
}


function _CheckShowTypeA_A($show_id)
{
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$showDE = $tableModaShows->getShowByShowIdNoLimit($show_id);
		if(!$showDE)
				FI_alert("非法连接！","?Controller=ModaIndex");
		else
		{
				if(FI_CheckAdminRoot())
						return showDE;
				if($showDE['username'] != $userName)
						FI_alert("非法操作！","?Controller=ModaIndex");
		}	
}

function _CheckShowDiscEditRight($Id)	
{
		$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
		$sdat = $tableModaShowDiscuss->getShowDiscussByID($Id);
		if($sdat)
		{
				if(FI_CheckAdminRoot())
					return true;
				$userName = $_SESSION["Zend_Auth"]['storage']->username;
				if($userName != $clubCC['uname'])	
					FI_alert("错误，您没有修改权限","?Controller=ModaIndex");
				else
					return true;
		}else
				FI_alert("错误，回复不存在","?Controller=ModaIndex");
	
}


function _GetShowDiscEditRight($Id)	
{
		$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
		$sdat = $tableModaShowDiscuss->getShowDiscussByID($Id);
		if($sdat)
		{
				if(FI_CheckAdminRoot())
					return true;
				$userName = $_SESSION["Zend_Auth"]['storage']->username;
				if($userName != $clubCC['uname'])	
					return false;
				else
					return true;
			
		}
		else
				return false;
}

function _CheckByClubCallEditRight($Id)	
{	
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');			
		$clubCC = $tableModaClubCall->getCallById($Id);
		if($clubCC)
		{
				if(FI_CheckAdminRoot())
					return true;
				$userName = $_SESSION["Zend_Auth"]['storage']->username;
				if($userName != $clubCC['uname'])	
					FI_alert("错误，您没有修改权限","?Controller=ModaIndex");
				else
					return true;
			
		}else
				FI_alert("错误，回复不存在","?Controller=ModaIndex");
		
}

function _GetByClubCallEditRight($Id)	
{	
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');			
		$clubCC = $tableModaClubCall->getCallById($Id);
		if($clubCC)
		{
				if(FI_CheckAdminRoot())
					return true;
				$userName = $_SESSION["Zend_Auth"]['storage']->username;
				if($userName != $clubCC['uname'])	
					return false;
				else
					return true;
			
		}else
				return false;
}
		
function _CheckEventById($event_id)	
{
		  $tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
		  $modaEvent  = $tableModaEvent->find("event_id = ".$event_id."");
		  if(!$modaEvent)
				FI_alert("错误，活动不存在","?Controller=ModaIndex");
}		

function _CheckClubCallTypeE($clubId)	
{	
		$tableModaClub = FLEA::getSingleton('Table_ModaClub');
		$clubDt = $tableModaClub->getDetailByClubId($clubId);
		if(!$clubDt)
			FI_alert("非法操作","?Controller=ModaIndex");
		
		if(FI_CheckAdminRoot())
			return true;
		
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');			
		$clubCC = $tableModaClubCall->getAdminCallByClubId($clubId);
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		if($userName != $clubCC['uname'])	
			FI_alert("非法操作","?Controller=ModaIndex");

}//"http://passport.we54.com/Index/login?forward=".urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])


function _GetClubCallTypeE($clubId)	
{	
		$tableModaClub = FLEA::getSingleton('Table_ModaClub');
		$clubDt = $tableModaClub->getDetailByClubId($clubId);
		if(!$clubDt)
			return false;
		
		if(FI_CheckAdminRoot())
			return true;
			
		$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');			
		$clubCC = $tableModaClubCall->getAdminCallByClubId($clubId);
		
		$userName = $_SESSION["Zend_Auth"]['storage']->username;
		if($userName != $clubCC['uname'])	
			return false;
		else
			return true;

}

function _VideoCover($str)
{	
		if(empty($str))
			return;
		if($str=="") return $str;
			  $str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
		return $str;
}


function _UbbCover($str)
{
		FLEA::loadClass('Lib_Ubb');
		$Ubb = new Lib_Ubb();
		$Ubb->setString($str);
		$str2 = $Ubb->parse();
		return $str2;
}
	
function _checkEXTuser()
{
		if($_SESSION["Zend_Auth"]['storage']->username)
		{
		
				if(FI_CheckAdminRoot())
					return true;
				else
				{
					$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
					$username = $_SESSION["Zend_Auth"]['storage']->username;
					/*禁言
					*/
					$memDd = $tableCdbmembers->_getUserByUsername($username);
					
					if($memDd['groupid'] == 4)
						FI_alert("您被禁言，请联系管理员","/");
					
					if($memDd['groupid'] == 22 or $memDd['extgroupids'] == 22)
						return true;
					else
						$this->_checkUserAmin();
				}
		  }
		  else
				FI_alert("请登录后操作","http://passport.we54.com/Index/login?forward=".urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));exit();
	}



function _getEXTuser()
{
		if($_SESSION["Zend_Auth"]['storage']->username)
		{
				if(FI_CheckAdminRoot())
					return true;
				else
				{
					$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
					$username = $_SESSION["Zend_Auth"]['storage']->username;
					/*禁言
					*/
					$memDd = $tableCdbmembers->_getUserByUsername($username);
					if($memDd['groupid'] == 4)
						return false;
					
					if($memDd['groupid'] == 22 || $memDd['extgroupids'] == 22)
						return true;
					else
						$this->_getUserAmin();
				}
		}
		else
				return false;
	}

function _getCommonUser()
{
		if($_SESSION["Zend_Auth"]['storage']->username)
		{
				if(FI_CheckAdminRoot())
					return true;
				else
				{
					$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
					$username = $_SESSION["Zend_Auth"]['storage']->username;
					/*禁言
					*/
					$memDd = $tableCdbmembers->_getUserByUsername($username);
					if($memDd['groupid'] == 4)
						return false;
					else
						return true;
				}	
		}
		else
				return false;
}


function cut_str($sourcestr,$cutlength) 
{ 
   $returnstr=''; 
   $i=0; 
   $n=0; 
   $str_length=strlen($sourcestr);//字符串的字节数 
   while (($n<$cutlength) and ($i<=$str_length)) 
   { 
      $temp_str=substr($sourcestr,$i,1); 
      $ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码 
      if ($ascnum>=224)    //如果ASCII位高与224，
      { 
         $returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符         
         $i=$i+3;            //实际Byte计为3
         $n++;            //字串长度计1
      }
      elseif ($ascnum>=192) //如果ASCII位高与192，
      { 
         $returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
         $i=$i+2;            //实际Byte计为2
         $n++;            //字串长度计1
      }
      elseif ($ascnum>=65 && $ascnum<=90) //如果是大写字母，
      { 
         $returnstr=$returnstr.substr($sourcestr,$i,1); 
         $i=$i+1;            //实际的Byte数仍计1个
         $n++;            //但考虑整体美观，大写字母计成一个高位字符
      }
      else                //其他情况下，包括小写字母和半角标点符号，
      { 
         $returnstr=$returnstr.substr($sourcestr,$i,1); 
         $i=$i+1;            //实际的Byte数计1个
         $n=$n+0.5;        //小写字母和半角标点等与半个高位字符宽...
      } 
   } 
         if ($str_length>$cutlength){
          $returnstr = $returnstr . "...";//超过长度时在尾处加上省略号
      }
    return $returnstr; 

}

	function actionPrePicUpload()
	{
				if($_GET['show_id'])
					$show_id = (int)$_GET['show_id'];
				if($_GET['user_id'])
					$user_id = (int)$_GET['user_id'];
					
				if($_POST['show_id'])
					$show_id = (int)$_POST['show_id'];
				if($_POST['user_id'])
					$user_id = (int)$_POST['user_id'];
				$visitUser_id = $user_id;
				$result = FI_TestSysUser("",$user_id,1,1);
				$showd = FI_ShowAdimnRoot($show_id,1);
				
				
				$show_img = $showd['show_img'];
				$temp_img = $showd['temp_img'];
				$dst_w = 500;
				$dst_h = 400;
				if($this->_isPost())
				{
							$urllink = "?Controller=ShowIndex&action=PrePicUpload&user_id=".$user_id."&show_id=".$show_id;
							$upload_name = 'file_tmp';
							$pathname = 'showFaceUpload/'."temp_img".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
							$uploadfile = FI_ImgUpLoad($upload_name,$pathname,$dst_w,$dst_h,$urllink,1,1);
							if($uploadfile)
							{
									if($showd['temp_img'])
										unlink($showd['temp_img']);
									$showd['temp_img']	=	$uploadfile;
									$temp_img = $uploadfile;
									$tableModaShows = FLEA::getSingleton('Table_ModaShows');
									$suc = $tableModaShows->save($showd);
									FI_changePage($urllink);
							}
				  }
				  
				  
				  
				//include(APP_DIR."/showView/PrePicUpload.html");
				//版本切换-by zh
				if($_COOKIE['moda_version'] == 'version_2')
					include(APP_DIR."/".$_COOKIE['moda_version']."/PrePicUpload.html");
				elseif($_COOKIE['moda_version'] == 'version_1')
					include(APP_DIR."/showView/PrePicUpload.html");
				else
					include(APP_DIR."/version_2/PrePicUpload.html");
	}
	
	
	function actionPostFacePicUpload()
	{
				if((int)$_POST['show_id'] == "")
					FI_alert("缺少参数","/");
				if((int)$_POST['user_id'] == "")
					FI_alert("缺少参数","/");
		
				if($_POST['show_id'])
					$show_id = (int)$_POST['show_id'];
				if($_POST['user_id'])
					$user_id = (int)$_POST['user_id'];
				$visitUser_id = $user_id;
				
				$showd = FI_ShowAdimnRoot($show_id,1);
				/*
				*/
				$backurl = "?Controller=ShowIndex&action=PrePicUpload&user_id=".$user_id."&show_id=".$show_id;
				$targ_x = $_POST['x']*$_POST['rw']/$_POST['pw'];
				$targ_y = $_POST['y']*$_POST['rh']/$_POST['ph'];
				$targ_w = $_POST['w']*$_POST['rw']/$_POST['pw'];
				$targ_h = $_POST['h']*$_POST['rh']/$_POST['ph'];
				$fd_w = 298;
				$fd_h = 198;
				/*
				*/
				if($showd['temp_img'] == "")
					FI_alert("请选择图片，上传后剪切保存！",$backurl);
				$src = $_POST['bigImage'];
				if(!$src)
					FI_alert("参数错误！",$backurl);
				$src = FI_CutImgUpLoad($src,$targ_x,$targ_y,$targ_w,$targ_h,$backurl,1,$fd_w,$fd_h);
				/*
				*/	
				$tableModaShows = FLEA::getSingleton('Table_ModaShows');
				if($showd['show_img'])
					unlink($showd['show_img']);
				$showd['show_img'] = $src;
				$showd['temp_img'] = "";
				$showd['available'] = 1;
				$showd['public'] = 1;
				$tableModaShows->updatetar($showd);
				//Fi_incresActiveNum("",2,true);
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				$result = $tableModaUser->find("user_id='".$user_id."'");
				if(!$result)
					FI_alert("出错了！",$backurl);
				$result['showcount'] =$result['showcount']+1 ;
				$tableModaUser->update($result);
				
//				if(showd['main'] == 1)
//				{
//					$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
//					$rankshow['head_img'] = $showd['show_img'];
//					$tableModaRankShow->updateByConditions("user_id = ".$showd['user_id'],$rankshow);
//				}
				
				FI_changePage("?Controller=ShowIndex&action=ShowsEidt&user_id=".$user_id."&show_id=".$show_id."");
	
	}

	function actionAddUserImg()
	{
				if($_GET['user_id'])
					$user_id = (int)$_GET['user_id'];
					
				if($_POST['user_id'])
					$user_id = (int)$_POST['user_id'];
				$visitUser_id = $user_id;
				$dst_w = 500;
				$dst_h = 400;
				if($_GET['username'])
				{
						$username = $_GET['username'];
						$showd = FI_TestSysUser($username,"",1,1);
						$show_img = $showd['head_img'];
						$temp_img = $showd['person_pic'];
				}
				else
				if($_POST['username'])
				{
						$username = $_POST['username'];
						$showd = FI_TestSysUser($username,"",1,1);
						FI_CheckCommonRight($user_id,1);
						/*
						*/
						$urllink = "?Controller=ShowIndex&action=AddUserImg&username=".$username;
						$upload_name = 'file_tmp';
						$pathname = 'head_img/'."head_img".strtotime(date('Y-m-d H:i:s')).rand(1000,9999);
						$uploadfile = FI_ImgUpLoad($upload_name,$pathname,$dst_w,$dst_h,$urllink,1,1);
						if($uploadfile)
						{
								if($showd['person_pic'])
									unlink($showd['person_pic']);
								$showd['person_pic']	=	$uploadfile;
								
								$Table_nymcUser = FLEA::getSingleton('Table_ModaUser');
								$suc = $Table_nymcUser->update($showd);
								
								FI_changePage($urllink);
						}
				}
				else
						 FI_alert("参数错误 ！","/");
				//include(APP_DIR."/showView/addUserImg.html");
				//版本切换-by zh
				if($_COOKIE['moda_version'] == 'version_2')
					include(APP_DIR."/".$_COOKIE['moda_version']."/addUserImg.html");
				elseif($_COOKIE['moda_version'] == 'version_1')
					include(APP_DIR."/showView/addUserImg.html");
				else
					include(APP_DIR."/version_2/addUserImg.html");
	}
	/*
	*/
	function actionPostAddUserImg()
	{
				$username = $_POST['username'];
				$showd = FI_TestSysUser($username,"",1,1);
				FI_CheckCommonRight($showd['user_id'],1);
				/*
				*/
				$backurl = "?Controller=ShowIndex&action=AddUserImg&username=".$username;
				$targ_x = $_POST['x']*$_POST['rw']/$_POST['pw'];
				$targ_y = $_POST['y']*$_POST['rh']/$_POST['ph'];
				$targ_w = $_POST['w']*$_POST['rw']/$_POST['pw'];
				$targ_h = $_POST['h']*$_POST['rh']/$_POST['ph'];
				$fd_w = 138;
				$fd_h = 138;
				/*
				*/
				if($showd['person_pic'] == "")
					FI_alert("请选择图片，上传后剪切提交！",$backurl);
				$src = $_POST['bigImage'];
				if(!$src)
					FI_alert("参数错误！",$backurl);
				$src = FI_CutImgUpLoad($src,$targ_x,$targ_y,$targ_w,$targ_h,$backurl,1,$fd_w,$fd_h);
				/*
				*/	
				$Table_nymcUser = FLEA::getSingleton('Table_ModaUser');
				if($showd['head_img'])
					unlink($showd['head_img']);
				$showd['head_img'] = $src;
				$showd['person_pic'] = "";
				$Table_nymcUser->update($showd);
				
				FI_changePage("?Controller=ModaIndex&action=GeRenZiLiao&id=".$showd['user_id']);
	}



	
	function actionFans()
	{
			$visitUser_id = $_GET['user_id'];
			
			$DataSource = $this->_HeaderCommonRead();
			$result = $DataSource['result'];
		
			$user_id = $_GET['user_id'];
			$Table_ModaFans = FLEA::getSingleton('Table_ModaFans');
			
			$pagesize	=	36;
			$conditions	=	"uid=".$user_id;
			$sortby		=	"dateline DESC";
			FLEA::loadClass('Lib_ModaPager');
		//	$page	=	new Lib_ModaPager( $Table_ModaFans, $pagesize, $conditions , $sortby );
			if($_COOKIE['moda_version'] == 'version_2'){
				FLEA::loadClass('Lib_ModaPager2');
				$page	=	new Lib_ModaPager2( $Table_ModaFans, $pagesize, $conditions , $sortby );	
			}
			elseif($_COOKIE['moda_version'] == 'version_1'){
				FLEA::loadClass('Lib_ModaPager');
				$page	=	new Lib_ModaPager( $Table_ModaFans, $pagesize, $conditions , $sortby );
			}
			else{
				FLEA::loadClass('Lib_ModaPager2');
				$page	=	new Lib_ModaPager2( $Table_ModaFans, $pagesize, $conditions , $sortby );
			}
			$rowset	=	$page->rowset;	
			
			$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
			$tableCdbmemberfields = FLEA::getSingleton('Table_Cdbmemberfields');
			$temp = 0;	
			foreach($rowset as $dat)
			{
					$cdbmfi = $tableCdbmemberfields->_getUserByUID($dat['fuid']);
					if(strstr($cdbmfi['avatar'], "http"))
						$rowset[$temp]['head_img'] = $cdbmfi['avatar'];
					else
					{
						if($cdbmfi['avatar'])
							$rowset[$temp]['head_img'] = "http://bbs.we54.com/".$cdbmfi['avatar'];
						else
							$rowset[$temp]['head_img'] = "/thumb_image/temp.jpg";
					}
					$temp++;
			}
			
			//include(APP_DIR . '/tow_view/Fans.html');	
			//版本切换-by zh
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/Fans.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/Fans.html");
			else
				include(APP_DIR."/version_2/Fans.html");
	}


	/*
	管理员用户
*/
function _CheckAdminRoot()
{

		if($_SESSION["Zend_Auth"]['storage']->username == 'moda' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='aaa' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='NewYouth' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='零四夭夭宝儿' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='浮游生物' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='小羊仔' )
			return true;
		else
			return false;		
	
}







}
?>
