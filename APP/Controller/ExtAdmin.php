<?php
include 'Fun_Include.php';

FLEA::loadClass('Controller_BoBase');
class Controller_ExtAdmin extends Controller_BoBase
{
		function Controller_ExtAdmin()
		{
				parent::Controller_BoBase();
				
				if(!FI_CheckAdminRoot())
				{
					FI_alert("权限不够，您可能没有登录！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/?Controller=ExtjsAdmin"));
				}		
		}
			
		function actionIndex()
		{
				$tableModaDoor = FLEA::getSingleton('Table_ModaDoor');	
				if($_POST)
				{
					  if($_POST['nu'])
					  {
						  $nu = $_POST['nu'];
						  $addr = $_POST['addr'];
						  $filename = "img_".$nu;
						  $filelink = "img".$nu."_link";
						  $img = FI_directUpLoad("upload","img/","?Controller=ExtAdmin",0);
						  if($img)
							  $data[$filename] = $img;
						  if($addr)
							  $data[$filelink] = $addr;
					  }
					  else
					  if($_POST['status'])
						 $data['status'] = $_POST['status'];
					  else
					  	 FI_alert("错误","?Controller=ExtAdmin");
					  $isfind = $tableModaDoor->find("user_id = -1");
					  if($img)
						  unlink($isfind[$filename]);
					  $suc = $tableModaDoor->updateByConditions("user_id = -1",$data);
					  FI_changePage("?Controller=ExtAdmin");
					  //dump($img);
					  
				}
				$doorDat = $tableModaDoor->find('user_id = -1');
				
				
				$Table_ModaAds = FLEA::getSingleton('Table_ModaAds');	
				$ads_uproll = $Table_ModaAds->findAll("type = '首页A'",'id desc');
				include(APP_DIR."/ExtAdminView/index.html");
				
		}
		
		
		function actionPostads()
		{
				$Table_ModaAds = FLEA::getSingleton('Table_ModaAds');	
				if($_POST)
				{
					$id = $_POST['id'];
					$ad = $Table_ModaAds->find("`id` = '$id'");
					if($ad)
					{
						$oldimage = $ad['image'];
					}
					else
					{
						$ad['type'] = $_POST['type'];
						$ad['time'] = time();
					}
					$img = FI_directUpLoad("image","img/","?Controller=ExtAdmin",0);
					if($_POST['link'])
						$ad['link'] = $_POST['link'];
					if($img)
						$ad['image'] = $img;
					if($img && $oldimage)
						unlink($oldimage);
					$suc = $Table_ModaAds->save($ad);
				}
				FI_changePage("?Controller=ExtAdmin");
		}
		
		
			
		function actionOtherads()
		{
				include(APP_DIR."/ExtAdminView/otherads.html");
		}
		
		
		function actionOnesPices()
		{
				$table = FLEA::getSingleton('Table_ModaUser');
				if($_GET['keyword'])
				{
						$key = $_GET['keyword'];
						$serdat = $table->find("`truename` = '$key' and `pass` = 1");
				}
				else
				{
						$pagesize	=	15;
						$conditions	=	"pass = 1";
						$sortby		=	"user_id DESC";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
						$rowset	=	$page->rowset;
				}
		
				include(APP_DIR."/ExtAdminView/onespices.html");
		}
		
		
		function actionOnesPicesOne()
		{
				$tableModaDoor = FLEA::getSingleton('Table_ModaDoor');	
				if($_POST)
				{
					  $user_id = $_POST['user_id'];
					  
					  if($_POST['nu'])
					  {
						  $nu = $_POST['nu'];
						  $addr = $_POST['addr'];
						  $filename = "img_".$nu;
						  $filelink = "img".$nu."_link";
						  $img = FI_directUpLoad("upload","img/","?Controller=ExtAdmin&action=OnesPicesOne&user_id=".$user_id,0);
						  if($img)
							  $data[$filename] = $img;
						  if($addr)
							  $data[$filelink] = $addr;
					  }
					  else
					  if($_POST['status'])
						 $data['status'] = $_POST['status'];
					  else
					  	 FI_alert("错误","?Controller=ExtAdmin");
					
					  $data['user_id'] = $user_id;
					  $isfind = $tableModaDoor->find("user_id = ".$user_id);
					  if($isfind)
					  {
						  	  if($img)
								  unlink($isfind[$filename]);
							  $suc = $tableModaDoor->updateByConditions("user_id = ".$user_id,$data);
					  }
					  else
							  $suc = $tableModaDoor->create($data);
					  
					  FI_changePage("?Controller=ExtAdmin&action=OnesPicesOne&user_id=".$user_id);
					  //dump($img);
					  
				}
			
				$user_id = $_GET['user_id'];
				$table = FLEA::getSingleton('Table_ModaUser');
				$user = $table->find("user_id = ".$user_id);
				$doorDat = $tableModaDoor->find("user_id = ".$user_id);
				include(APP_DIR."/ExtAdminView/onespices_one.html");
		}
		
		
		
		
		
		function actionCooperatrion()
		{
				$table = FLEA::getSingleton('Table_ModaUser');
				if($_POST)
				{
						$username = $_POST['username'];
						$data['username'] = $username;
						$data['pass'] = 11;
						$suc = $table->updateByConditions("username = '".$data['username']."'",$data);
						if($suc)
							  FI_changePage("?Controller=ExtAdmin&action=Cooperatrion");
				}
				$pagesize	=	15;
				$conditions	=	"pass = 11";
				$sortby		=	"user_id DESC";
				FLEA::loadClass('Lib_NewPager');
				$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
				$rowset	=	$page->rowset;
				include(APP_DIR."/ExtAdminView/cooperation.html");
		}
		
		
		
		
		function actionHotup()
		{
				$table = FLEA::getSingleton('Table_ModaExt');
				$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
				
				if($_POST)
				{
						$username = $_POST['username'];
						$data['username'] = $username;
						$data['status'] = $_POST['status'];
						$data['huoyuedu'] = $_POST['huoyuedu'];
						$suc = $table->updateByConditions("username = '".$data['username']."'",$data);
						if($suc)
							  FI_changePage("?Controller=ExtAdmin&action=Hotup");
				}
				
				if($_GET['keyword'])
				{
						$key = $_GET['keyword'];
						$temuser = $Table_ModaUser->find("truename like '%".$key."%' and (pass = 1 or pass = 11)");
						$serdat = $table->find("user_id = ".$temuser['user_id']);
						if($serdat)
						$serdat['truename'] = $temuser['truename'];
						
				}
				else
				{
						$pagesize	=	15;
						$conditions	=	"";
						$sortby		=	"";
						FLEA::loadClass('Lib_NewPager');
						$page	=	new Lib_NewPager( $table, $pagesize, $conditions , $sortby );
						$rowset	=	$page->rowset;
						
						$i = 0;
						foreach($rowset as $one)
						{
							$user = $Table_ModaUser->find("username = '".$one['username']."'");
							$rowset[$i]['truename'] = $user['truename'];
							$i++;
						}

				}
				
				
				include(APP_DIR."/ExtAdminView/hotup.html");
		}
		
		
		
		function actionRunsqlline() 
		{
			echo "runsqlline";
				if($_POST)
				{
					echo $_POST['sqlvalue'];
					 $sqlvalue = $_POST['sqlvalue'];
					 $tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
					 $res = $tableModaEvent->execute($sqlvalue);
					 dump($res);
				}
				include(APP_DIR."/tow_view/runsqline.html");
		}
		
		
		
		
		


}