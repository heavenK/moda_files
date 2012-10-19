<?php

include 'Fun_Include.php';

FLEA::loadClass('Controller_BoBase');
class Controller_ModaIndex extends Controller_BoBase
{
	
	function Controller_ModaIndex()
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
	
	
    /**
     * ��ʾ����
     */	 
    function actionIndex()
	{
			/*��������չʾ�����
			*/
			$tableshowips = FLEA::getSingleton('Table_ModaShowIps');	
			$tableshowips->incresAll();
			
			$tableModaUser = FLEA::getSingleton('Table_ModaUser');			
			$result = $tableModaUser->findAll("pass = 1 order by user_id desc limit 0,15");
			$resultuser = $tableModaUser->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");
			
//			$table1 = FLEA::getSingleton('Table_ModaRanks');	
//			$result1=$table1->findAll("","rank_id desc","1");
			
			$cooperations = $tableModaUser->findAll("pass = 11 order by user_id desc limit 0,3");
					
			//��ѯ��̸ǰ4
			$table3 = FLEA::getSingleton('Table_ModaNews');
			$news = $table3->find("","news_id desc");
			
			/*չʾ
			*/
			$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
			//$showDatass = $tableModaShow->getShowsOrderByHomePage();
			$showDatass = $tableModaShow->findAll("available = 1 and public = 1","show_id DESC limit 24");
			/*�����û��ǳƵ�չʾ����
			*/
			$temp = 0;			
			foreach($showDatass as $shdat)
			{
					$nickname = $tableModaUser->getUserByUname($shdat['username']);
					$showDatass[$temp]['nickname'] = $nickname['nickname'];
							
					$temp ++;
			}
				
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			//$result2=$tableModaRankShow->getTopGirlShowByHomePage();
			$result2 = $tableModaRankShow->findAll("","Id desc limit 0,12");
			$resultks = $tableModaRankShow->findAll("","Id desc limit 0,9");
			$tableModaRankShow->updateHeadImg($result2);
				
			/*������
			*/
			$tableModaDoor = FLEA::getSingleton('Table_ModaDoor');	
			$doorDat = $tableModaDoor->IndexTest();
			//�������
			$Table_ModaAds = FLEA::getSingleton('Table_ModaAds');	
			$ads_uproll = $Table_ModaAds->findAll("type = '��ҳA'",'id desc');
			//
			$tableModaClub = FLEA::getSingleton('Table_ModaClub');
			$clubDa = $tableModaClub->findAll("","dateline DESC limit 18");
			$tableCdbmembers = FLEA::getSingleton('Table_Cdbmembers');
			$temp = 0;	
			
			foreach($clubDa as $dat)
			{
					/*����
					*/	
					$memDd = $tableCdbmembers->_getUserByUsername($dat['creater_name']);
					$clubDa[$temp]['groupid'] = $memDd['groupid'];
					$nickname = $tableModaUser->getUserByUserId($dat['uid']);
					$clubDa[$temp]['nickname'] = $nickname['nickname'];	
					/*��������
					*/
					$title = $dat['title'];
					$pieces = explode("^?",stripslashes($title)); 
					$clubDa[$temp]['title'] = $pieces[0];
								
					$temp ++;
			}
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$modaEvent  = $tableModaEvent->find("","event_id desc");
			$xgirldat  = $tableModaEvent->find("channeltype = 2","event_id desc");
			$Table_ModaExt = FLEA::getSingleton('Table_ModaExt');
			$userext = $Table_ModaExt->findAll("status = 0","(huoyuedu+fensishu) DESC limit 0,3");
			
			$i = 0;
			foreach($userext as $extdat)
			{
					$tu = $tableModaUser->find("user_id = ".$extdat['user_id']);
					$userext[$i]['nickname'] = $tu['nickname'];
					$userext[$i]['face'] = $tu['head_img'];
					$userext[$i]['truename'] = $tu['truename'];

					$userext[$i]['extdats'] = FI_gethuoyuedufensishu($extdat['user_id']);

					$i++;
			}
			
			//moda �汾�л�
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/index.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/index.html");
			else
				include(APP_DIR."/version_2/index.html");
    }
	
	
	
		
	/*������ת
	*/
	 function actionJumpUrl()
	 {
	 
		$domain = $this->_convertSpecialChars($_GET['domain']);
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');	
		$userD = $tableModaUser->getUserIDByAddress($domain);
		
		FI_changePage("?controller=ModaIndex&action=Person&id=".$userD['user_id']);
	
	}
	
	
	
	
	
	function actionModaAbout()
	{
	//	include(APP_DIR."/showView/modaAbout.html");
	//	�汾�л�-by zh
		if($_COOKIE['moda_version'] == 'version_2')
			include(APP_DIR."/".$_COOKIE['moda_version']."/modaAbout.html");
		elseif($_COOKIE['moda_version'] == 'version_1')
			include(APP_DIR."/showView/modaAbout.html");
		else
			include(APP_DIR."/version_2/modaAbout.html");
	}
	
	
	function actionModaEventList()
	{
	
		$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
		$modaEvent  = $tableModaEvent->findAll("channeltype = 0","event_id desc");
	//	include(APP_DIR."/View/modaEventList.html");
	//	�汾�л�-by zh
		if($_COOKIE['moda_version'] == 'version_2')
			include(APP_DIR."/".$_COOKIE['moda_version']."/modaEventList.html");
		elseif($_COOKIE['moda_version'] == 'version_1')
			include(APP_DIR."/View/modaEventList.html");
		else
			include(APP_DIR."/version_2/modaEventList.html");
	
	}
	
	
	function actionModaEvent()
	{
			$event_id = (int)$_GET['event_id'];
			if(!$event_id)
				$this->_alert("�Ƿ�����","?Controller=ModaIndex");
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$modaEvent  = $tableModaEvent->find("event_id =".$event_id."");
			if(!$event_id)
				$this->_alert("�ҳ������","?Controller=ModaIndex");
			//dump($modaEvent);
			//$modaEvent['content'] = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="970" height="630" id="cc_9875622CEA4DFE7C"><param name="movie" value="http://union.bokecc.com/flash/single/357CF6ABD01337D7_763ACDF20012741F_false_6D00EE7FEDC90B8F_1/player.swf" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><embed src="http://union.bokecc.com/flash/single/357CF6ABD01337D7_763ACDF20012741F_false_6D00EE7FEDC90B8F_1/player.swf" width="970" height="630" name="cc_9875622CEA4DFE7C" allowFullScreen="true" allowScriptAccess="always" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"/></object><div></div><p><img alt="" src="/userfiles/6777(1).jpg">&nbsp;</p><p><img alt="" src="/userfiles/6777(1).jpg">&nbsp;</p>';
			
			
			list($a,$b) = explode('<div></div>',$modaEvent['content']);
			
			list($a1,$b2) = explode('application/x-shockwave-flash',$a);
			
			list($a2,$b1) = explode('<div></div>',$modaEvent['content']);

			if(!$b1){
				$b1 = $modaEvent['content'];
			}
		
			//include(APP_DIR."/View/modaEvent.html");
			//�汾�л�
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/modaEvent.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/View/modaEvent.html");
			else
				include(APP_DIR."/version_2/modaEvent.html");
		
	}
	
	
	
	
	function actionModaDoor()
	{
			
		
			if($this->_CheckAdminRoot())
			{
			
			
					//$checkfix	=	get_app_inf('FileExts');
					$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
					$uploaddir ='img/';	
					
					
					
					$temp =1;
					foreach($_FILES as $upload_name => $val)
					{
						//dump($upload_name);
						
						$file_name = $_FILES[$upload_name]['name'];
						if($file_name == '')
						{ 
							$temp+=1;	
							
						}
						else
						{
							$file_postfix = substr($file_name,strrpos($file_name,"."));
							$newname	=	$uploaddir."door_".$temp."_".rand(1000,9999).time().$file_postfix;
							//�ļ����ͼ��
							if(!in_array($file_postfix,$limittyppe))
							{
								echo "�ļ����ʹ���";
								$temp+=1;	
								
							}
							else
							{
								/*//������޸���Ϣ
								if($data[$this->primaryKey]!="" and $file_name != ''){
									$trow	=	$this->find($data[$this->primaryKey]);
									unlink($trow[$upload_name]);
								}*/
								//echo $newname;
								$temp_name = $_FILES[$upload_name]['tmp_name']; 		 
								$result = move_uploaded_file($temp_name,$newname);	
								$data[$upload_name]	=	$newname;
								$temp+=1;	
								//dump($data);
								
							}
							
						}	
					}	
					//dump($_POST);
					
				$data["status"] = (int)$_POST['status'];
				$data["img1_link"] = $_POST['img1_link'];
				$data["img2_link"] = $_POST['img2_link'];
				$data["img3_link"] = $_POST['img3_link'];
				$data["img4_link"] = $_POST['img4_link'];
				$data["img5_link"] = $_POST['img5_link'];
				$data["img6_link"] = $_POST['img6_link'];
				$data["user_id"] = (int)$_POST['user_id'];
					
//				dump($data["status"]);	
//				exit;	
				$tableModaDoor = FLEA::getSingleton('Table_ModaDoor');
				
				if(1 == $data["status"])
				{
					$tt = $tableModaDoor->test();
					if($tt)
					{
						//dump($data);
						$tableModaDoor->updatetar($data);
					
					}
					else
					{
						$tableModaDoor->savetar($data);
					}
					
				}
				else
				{
					
					$tt = $tableModaDoor->getatest(-1);
					if($tt)
					{
						dump($data);
						$tableModaDoor->updatetar($data);
					
					}
					else
					{
						$tableModaDoor->savetar($data);
					}
				}	

					//exit;
				FI_changePage("?Controller=ModaIndex");
			}
			else
			{
				$this->_alert("�Ƿ�����","?Controller=ModaIndex");
				exit;
			}

	
	}
	
	
	
	//���а�����
	function actionLankUser(){
		//$table = FLEA::getSingleton('Table_ModaRankMes');
		$table1 = FLEA::getSingleton('Table_ModaUser');	
				$_SESSION['visited'];
			$result= $table1->find("user_id='".(int)$_GET['id']."'");
			if($_SESSION['visited']!=1){
					$table1->incrfield("user_id='".(int)$_GET['id']."'","liulanliang","1");
				$_SESSION['visited']=1;
			}
				$curUsername = $result['username'];
				
			
				
	
			/*��ȡTable_ModaFriend			
			*/		
			$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');	
			$friends = $tableModaFriend->getFriendWithModaUser($curUsername,1);
			
			//dump($friends);
			/*��ȡTable_ModaShows
			*/		
			$tableModaShows = FLEA::getSingleton('Table_ModaShows');

			$showData = $tableModaShows->getShowByUserName($curUsername);
		
		include(APP_DIR."/showView/ShowPersonal.html");

	}
	
	
	
	
	//��ҳ�����
	function actionUserIndex(){
		$table = FLEA::getSingleton('Table_ModaUser');
		$result=$table->find("user_id='".(int)$_GET['id']."'");

		//�������
		$table->incrfield("user_id='".(int)$_GET['id']."'","liulanliang","1");
		
	
	}
		
		
		
		
	//��������
	function actionGeRenZiLiao()
	{
				$table = FLEA::getSingleton('Table_ModaUser');
				$user_id = $_GET['id'];
				if(!$user_id)
					$user_id = $_POST['id'];
				$visitUser_id = $_GET['id'];
				if(!$visitUser_id)
					$visitUser_id = $_POST['id'];
					
				if($this->_isPost())
				{
							if($this->_CheckAdminRoot())
							{
									//$row = $table->getUserByUserId($_SESSION["Zend_Auth"]['storage']->temp_id);
							}
							else
									$row = $table->find("username = '".$_SESSION["Zend_Auth"]['storage']->username."'");
									
							$postuser_id = $_POST['user_id'];									
							if($this->_CheckAdminRoot())
									$row = $table->getUserByUserId($postuser_id);
							
							
							//FI_DEBUG($postuser_id,0);
							
							
							FI_CheckCommonRight($row['user_id'],1);			
									
									
							if($_POST['dpost'] == "address")
							{
									$checD = $table->find("address = '".$this->_convertSpecialChars($_POST['addr'])."'");
									if($checD)
									{
											if($user_id != $checD['user_id'])
													FI_alert('������ͻ������������',"?controller=ModaIndex&action=GeRenZiLiao&id=".$user_id."");
									}
									else
											$row['address'] = $this->_convertSpecialChars($_POST['addr']);
							}
							else
							{
									foreach($_POST as $key=>$val)
									{
											$_POST[$key]	=	$this->unescape($val);
											$row[$key]	=	$this->_convertSpecialChars($_POST[$key]);
									}
							}
							
					//		if($table->update($row))
							if($table->updateByConditions(array('user_id'=>$user_id),$row))				//modify by zh
									FI_changePage("?controller=ModaIndex&action=GeRenZiLiao&id=".$user_id."");
							else
									FI_alert('ʧ��',"?controller=ModaIndex&action=GeRenZiLiao&id=".$user_id."");
				}
				else
				{
					
							if((int)$_GET['id']=="")
									FI_alert("�Ƿ�����","?Controller=ModaIndex");
							$result = $table->find("user_id = '".(int)$_GET['id']."' And (pass = 1 or pass = 11)");
							if(!$result)
									FI_alert("�Ƿ�����","?Controller=ModaIndex");
							
				
							$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
							$rankshow = $tableModaRankShow->find("user_id = ".(int)$_GET['id']);
							if($rankshow['rank_id'] != 7)
							{
								
									$Table_ModaUserExt = FLEA::getSingleton('Table_ModaUserExt');
									$userext = $Table_ModaUserExt->find("user_id = ".(int)$_GET['id']);	
//									if(!$userext)
//									{
//											include(APP_DIR."/View/modauserdata.html");
//											exit;
//									}

									//FI_DEBUG($userext,0);

									if($this->_CheckAdminRoot())
									{
											$_SESSION["Zend_Auth"]['storage']->temp_id = $user_id;
											//include(APP_DIR."/View/modauserdata.html");
											//�汾�л�-by zh
											if($_COOKIE['moda_version'] == 'version_2')
												include(APP_DIR."/".$_COOKIE['moda_version']."/modauserdata.html");
											elseif($_COOKIE['moda_version'] == 'version_1')
												include(APP_DIR."/View/modauserdata.html");
											else
												include(APP_DIR."/version_2/modauserdata.html");
											exit;
									}		
									if(FI_CheckCommonRight($user_id,0))
											//include(APP_DIR."/View/modauserdata.html");
											//�汾�л�-by zh
											if($_COOKIE['moda_version'] == 'version_2')
												include(APP_DIR."/".$_COOKIE['moda_version']."/modauserdata.html");
											elseif($_COOKIE['moda_version'] == 'version_1')
												include(APP_DIR."/View/modauserdata.html");
											else
												include(APP_DIR."/version_2/modauserdata.html");
									else
									{
											$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And (pass = 1 or pass = 11)");
											//include(APP_DIR."/View/modauserdata2.html");
											//�汾�л�-by zh
											if($_COOKIE['moda_version'] == 'version_2')
												include(APP_DIR."/".$_COOKIE['moda_version']."/modauserdata2.html");
											elseif($_COOKIE['moda_version'] == 'version_1')
												include(APP_DIR."/View/modauserdata2.html");
											else
												include(APP_DIR."/version_2/modauserdata2.html");
									}		
									exit;

							}
									
									
							$result['city']=stripslashes($result['city']);
							$result['bloodtype']=stripslashes($result['bloodtype']);
							$result['starsings']=stripslashes($result['starsings']);
							$result['like']=stripslashes($result['like']);
							$result['likestar']=stripslashes($result['likestar']);
							$result['motto']=stripslashes($result['motto']);
							$result['announcement']=stripslashes($result['announcement']);
							$result['likeplace']=stripslashes($result['likeplace']);
							$result['wish_hope']=stripslashes($result['wish_hope']);
							if($this->_CheckAdminRoot())
							{
									$_SESSION["Zend_Auth"]['storage']->temp_id = $user_id;
									//include(APP_DIR."/View/grdasec.html");
									//�汾�л�-by zh
									if($_COOKIE['moda_version'] == 'version_2')
										include(APP_DIR."/".$_COOKIE['moda_version']."/grdasec.html");
									elseif($_COOKIE['moda_version'] == 'version_1')
										include(APP_DIR."/View/grdasec.html");
									else
										include(APP_DIR."/version_2/grdasec.html");
									exit();
							}		
								
								
							if(FI_CheckCommonRight($user_id,0))
									//include(APP_DIR."/View/grdasec.html");
									//�汾�л�-by zh
									if($_COOKIE['moda_version'] == 'version_2')
										include(APP_DIR."/".$_COOKIE['moda_version']."/grdasec.html");
									elseif($_COOKIE['moda_version'] == 'version_1')
										include(APP_DIR."/View/grdasec.html");
									else
										include(APP_DIR."/version_2/grdasec.html");
							else
							{
									$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And (pass = 1 or pass = 11)");
									//include(APP_DIR."/View/grdasec2.html");
									//�汾�л�-by zh
									if($_COOKIE['moda_version'] == 'version_2')
										include(APP_DIR."/".$_COOKIE['moda_version']."/grdasec2.html");
									elseif($_COOKIE['moda_version'] == 'version_1')
										include(APP_DIR."/View/grdasec2.html");
									else
										include(APP_DIR."/version_2/grdasec2.html");
							}		
				}		
		
	}
	
	
	
	function actionPostGerenzhiliao()
	{
				$table = FLEA::getSingleton('Table_ModaUser');
				$Table_ModaUserExt = FLEA::getSingleton('Table_ModaUserExt');
				if($this->_isPost())
				{
							if($this->_CheckAdminRoot())
							{
									//$data = $Table_ModaUserExt->find("user_id = ".$_SESSION["Zend_Auth"]['storage']->temp_id);
							}
							else
							{
									$data = $Table_ModaUserExt->find("username = '".$_SESSION["Zend_Auth"]['storage']->username."'");
							}
							
							$postuser_id = $_POST['user_id'];									
							if($this->_CheckAdminRoot())
									$data = $Table_ModaUserExt->find("user_id = ".$postuser_id);
							
							
							
							
							
							if(!$data)
							{
									$data['user_id'] = $postuser_id;
									$row = $table->find("user_id = ".$data['user_id']);
									$data['username'] = $row['username'];
									$Table_ModaUserExt->save($data);
							}
									
							FI_CheckCommonRight($data['user_id'],1);	
							if($_POST['dpost'] == "address")
							{
									$row['user_id'] = $_POST['user_id'];	
									$checD = $table->find("address = '".$this->_convertSpecialChars($_POST['addr'])."'");
									if($checD)
									{
											if($user_id != $checD['user_id'])
													FI_alert('������ͻ������������',"?controller=ModaIndex&action=GeRenZiLiao&id=".$user_id."");
									}
									else
											$row['address'] = $this->_convertSpecialChars($_POST['addr']);
									if($table->update($row))
									FI_changePage("?controller=ModaIndex&action=GeRenZiLiao&id=".$row['user_id']);
									else
									FI_alert('ʧ��',"?controller=ModaIndex&action=GeRenZiLiao&id=".$row['user_id']);
							}
							
							{
									foreach($_POST as $key=>$val)
									{
											$_POST[$key]	=	$this->unescape($val);
											$data[$key]	=	$this->_convertSpecialChars($_POST[$key]);
									}
							}
							$Table_ModaUserExt = FLEA::getSingleton('Table_ModaUserExt');
							
							
						//	$Table_ModaUserExt->update($data);
							$Table_ModaUserExt->updateByConditions(array('user_id'=>$data['user_id']),$data);
				}
	}
	
	
	
		//ת������
		function unescape($str) {
			$str = rawurldecode($str);
			preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|&#\d+?|.+/U",$str,$r);
			$ar = $r[0];
			foreach($ar as $k=>$v) {
				if(substr($v,0,2) == "%u")
					$ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,-4)));
				elseif(substr($v,0,3) == "&#x")
					$ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,3,-1)));
				elseif(substr($v,0,2) == "&#") {
					$ar[$k] = iconv("UCS-2","GBK",pack("n",preg_replace("/[^\d]/","",$v)));
				}
			}
			return join("",$ar);
		}    
		
		
		
		//����Ϣ
		function actionMessage(){
			$tableuser = FLEA::getSingleton('Table_ModaUser');
			$tablemember = FLEA::getSingleton('Table_Cdbmembers');
			$tablemessage = FLEA::getSingleton('Table_Message');
			//$_SESSION["Zend_Auth"]['storage']->username			
			if($_SESSION["Zend_Auth"]['storage']->username){
				//��ȡ������de��Ϣ
				$member = $tablemember->_getUserByUsername($_SESSION["Zend_Auth"]['storage']->username);
				//��ȡ������de��Ϣ
				$member1 = $tablemember->_getUserByUsername($this->_convertSpecialChars($_POST['username']));		
					
					
				
					$arr=array(
							'msgfrom'  => $_SESSION["Zend_Auth"]['storage']->username,
							'msgfromid' => $member['uid'],
							'msgtoid'  => $member1['uid'],
							'subject'  => $this->_convertSpecialChars($_REQUEST['subject']),
							'dateline' => time(),
							'message' => $this->_convertSpecialChars($_REQUEST['message']),
							);						
					$tablemessage->create($arr);					
					$this->_alert('���ͳɹ�');
				
			}else{
				$this->_alert("���½");
				
			}
		}
		
	
		
	function actionPerson()
	{
				$table = FLEA::getSingleton('Table_ModaUser');
				$tmpid = (int)$_GET['id'];
				if($tmpid == "")
				{
						if($_SESSION["Zend_Auth"]['storage']->username)
						{
								$result= $table->find("username='".$_SESSION["Zend_Auth"]['storage']->username."'And (pass = 1 or pass = 11)");
								if(!$result)
									$this->_alert('���û�������',"?controller=ModaIndex");
								else
									FI_changePage("?Controller=ModaIndex&action=Person&id=".$result['user_id']);
						}
				}
				if((int)$_GET['id'])
				{
						$id = (int)$_GET['id'];
//						$_SESSION['visited'];
						$result = $table->find("user_id= ".$id." And(pass = 1 or pass = 11)");				
						if(!$result)
							$this->_alert('���û����������Ա',"?Controller=ModaIndex");
						$table->incrfield("user_id='".(int)$_GET['id']."'","liulanliang","5");				
						$visitUser_id=$result['user_id'];
						
						$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
						$rankshow = $tableModaRankShow->find("user_id = ".$id);
						if($rankshow['rank_id'] != 7)
						{
								$Table_ModaUserExt = FLEA::getSingleton('Table_ModaUserExt');
								$modauserext = $Table_ModaUserExt->find("user_id = ".(int)$_GET['id']);	
						}
						
						$curUsername = $result['username'];
						$resultuser['user_id'] = $result['user_id'];
//						$tableModaFriend = FLEA::getSingleton('Table_ModaFriend');	
//						$friends = $tableModaFriend->getFriendWithModaUser($curUsername,1);
						
						$tableModaShows = FLEA::getSingleton('Table_ModaShows');
						$showData = $tableModaShows->getAvlShowByUserName($curUsername);
						$tableModaShows->findAll("username='".$userName."' and available = 1 and public = 1 and main = 0","created_on DESC");
						$mainshow = $tableModaShows->getMainShowById($curUsername);
						$tableModaShow = FLEA::getSingleton('Table_ModaClub');		
						$pagesize	=	21;
						$conditions	=	"uid=".$id."";
						$sortby		=	"dateline DESC";
						FLEA::loadClass('Lib_ModaPager');
						$page	=	new Lib_ModaPager( $tableModaShow, $pagesize, $conditions , $sortby );
						$rowset	=	$page->rowset;	
						$temp = 0;
						foreach( $rowset as $data)
						{
								$title = $data['title'];
								$pieces = explode("^?",stripslashes($title)); 
								$rowset[$temp]['title'] = $pieces[0];
								$temp++;
						}
						
						$visitUser_id = (int)$_GET['id'];
						$tableModaUser = FLEA::getSingleton('Table_ModaUser');		
						if($userdat['username'] != $_SESSION["Zend_Auth"]['storage']->username)
						{
								$userdat = $tableModaUser->getUserByUserId($visitUser_id);
								$tableModaUser->incrfield("user_id='".$visitUser_id."'","liulanliang","1");
						}	
						$tableModaDoor = FLEA::getSingleton('Table_ModaDoor');	
						$doorDat = $tableModaDoor->getatest($visitUser_id);
						$Table_ModaNews = FLEA::getSingleton('Table_ModaNews');
						$vcrdata = $Table_ModaNews->find("news_st = '".$result['truename']."'");
						$userext = FI_gethuoyuedufensishu($visitUser_id);
						
					//	include(APP_DIR."/tow_view/personal.html");
					//	�汾�л�-by zh
						if($_COOKIE['moda_version'] == 'version_2')
							include(APP_DIR."/".$_COOKIE['moda_version']."/personal.html");
						elseif($_COOKIE['moda_version'] == 'version_1')
							include(APP_DIR."/tow_view/personal.html");
						else
							include(APP_DIR."/version_2/personal.html");
				}
				else
						$this->_alert('�Ƿ�����',"?Controller=ModaIndex");
					
					
		
		}
		
		
		
		function actionAddfans()
		{
				$user_id = $_GET['user_id'];
				$user = FI_getuserinfo($user_id,1,"/");
				$mydat = FI_getmydata(1,"/");
				
				$data['uid'] = $user['user_id'];
				$data['uname'] = $user['username'];
				$data['fuid'] = $mydat->uid;
				$data['fusername'] = $mydat->username;
				$data['fnickname'] = $mydat->nickname;
				$data['status'] = 0;
				$data['note'] = NULL;
				$data['dateline'] = time();
				
				$Table_ModaFans = FLEA::getSingleton('Table_ModaFans');
				$suc = $Table_ModaFans->updatefans($user['username'],$mydat->username,$data);
				
				if($suc)
					FI_updatemodaext($user_id,'',2,"",1);
				FI_alert("��л����֧�֣�","?Controller=ModaIndex&action=Person&id=".$user_id);
				
		}
		
		
		//���а�ͶƱ
			function actionTouPiao()
			{
			
			
					$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
			
			
					
					$show_id = (int)$_GET['show_id'];
				
					$table = FLEA::getSingleton('Table_Pollips');	
					
					$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
						
					$rankShow = $tableModaRankShow->getTopGirlShowByShowId($show_id);
					
					$rankD = $tableModaRanks->getRankById($rankShow['rank_id']);
					
					//dump(date('Y-m-j'));
					//dump($rankD['overtime']);
					
					$tt = strtotime(date('Y-m-j'))-strtotime($rankD['overtime']);
					

					//dump($tt);
					
					if($tt>0)
					{
						$this->_alert("����ͶƱ��ѽ�����лл���Ĳ���");exit();
					}
					
					
					if(!$table->checkip()){
						$this->_alert("�������Ѿ�ͶƱ���ˣ�лл���Ĳ���");exit();
					}else{
					
						$rankShow['ticket']+=1;
						$tableModaRankShow->updatetarTicket($rankShow);
						$this->_alert("ͶƱ�ɹ�");
					}
					
			
			
			}
			
			
			
			
		//�༭������ҳͼƬ
			function actionPersonPic(){				
				
				$uploaddir ='uploadd/';
				
				
				$file_name = $_FILES['filebig']['name'];
				
				//$type=$_FILES['file']['type'];
				$file_postfix = substr($file_name,strrpos($file_name,"."));
				$newname	=	"/uploadd/".time()."person".$file_postfix;
				$size=$_FILES['filebig']['size'];
				$limittyppe	= array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
				
				if(!in_array($file_postfix,$limittyppe)){
					
					$this->_alert("���ϴ�ͼƬ");
					return false ;
					exit();
				}else{
						
						if($size>2048000)
						{
							$this->_alert("ͼƬ���ܳ���2M");
							return false ;
							exit();
						}
						
						
						
						 $temp_name = $_FILES['filebig']['tmp_name'];
						 
						 $result = move_uploaded_file($temp_name,".".$newname);
						 if($result)
						 {
						 
						 	$tableModaUser = FLEA::getSingleton('Table_ModaUser');		
							$result = $tableModaUser->find("user_id = '".(int)$_POST['user_id']."'");
							//Ĭ��ͼƬ��ɾ��
							
							if($result['person_pic'] && $result['person_pic']!="images/grsy_02.jpg"  ){
						
								unlink(".".$result['person_pic']);		
							}
						 
						 
						 	$result['person_pic'] = $newname;
						 	$tableModaUser->update($result);
						 
						 
						 	$this->_alert("�༭�ɹ�");
							
							return true;
							exit;
						 }else 
						 {
							return false;
							exit;
						 }
				 }
				 
	}


		//���а�
		function actionModaLank()
		{
			
			$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
			$rowset = $tableModaRanks->findAll("","rank_id DESC limit 2,1");
			$rowset2 = $tableModaRanks->findAll("","rank_id DESC limit 1,1");
			$rowset3 = $tableModaRanks->findAll("","rank_id DESC limit 0,1");

			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
	
			//include(APP_DIR."/View/moda-lank.html");
			//include(APP_DIR."/tow_view/topgirl.html");
			//moda �汾�л�
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/topgirl.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/topgirl.html");
			else
				include(APP_DIR."/version_2/topgirl.html");
		
		}
		
		
		
		//����Ϣ����
			function actionSendMes(){
				$this->_alert('�Լ����ܸ��Լ�����Ϣ');
			
			}
			function actionSendMes1(){
				$this->_alert('��¼�û��ſ��Է���Ϣ');
			
			}
		
		
		function actionNewAdd()
		{
				$table = FLEA::getSingleton('Table_ModaUser');
				$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");	
			
				$pagesize	=	24;
				$conditions	=	"pass = 1";
				$sortby		=	"passtime desc , user_id DESC";
				if($_COOKIE['moda_version'] == 'version_2'){
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $table, $pagesize, $conditions , $sortby );
				}
				elseif($_COOKIE['moda_version'] == 'version_1'){
					FLEA::loadClass('Lib_ModaPager');
					$page	=	new Lib_ModaPager( $table, $pagesize, $conditions , $sortby );
				}
				else{
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $table, $pagesize, $conditions , $sortby );
				}
				//FLEA::loadClass('Lib_ModaPager');
				//$page	=	new Lib_ModaPager( $table, $pagesize, $conditions , $sortby );
				$rowset	=	$page->rowset;
				$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
				$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
				$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
				$rankshow = $tableModaRankShow->getShowByMingci();
				
				foreach($rankshow as $st)//��ѭ��
				{
						$showD = $tableModaShow->getShowByShowId($st['show_id']);
						$temp = 0;
						foreach($rowset as $rt)//�û�ѭ��
						{
								if( $rt['user_id'] == $st['user_id'] )
								{
									$rankD = $tableModaRanks->getRankById($st['rank_id']);
									if($st['mingci'] == 1)
										$rowset[$temp]['mingci_img'] = $rankD['first_mark'];
									if($st['mingci'] == 2)
										$rowset[$temp]['mingci_img'] = $rankD['sec_mark'];
									if($st['mingci'] == 3)
										$rowset[$temp]['mingci_img'] = $rankD['thr_mark'];
									
								}
								$temp+=1;
						}
				}	
				
				//include(APP_DIR."/View/ShowModaer.html");
				//moda �汾�л�
				if($_COOKIE['moda_version'] == 'version_2')
					include(APP_DIR."/".$_COOKIE['moda_version']."/ShowModaer.html");
				elseif($_COOKIE['moda_version'] == 'version_1')
					include(APP_DIR."/View/ShowModaer.html");
				else
					include(APP_DIR."/version_2/ShowModaer.html");
		}
		
		
		
		function actionLiuLanMore(){
			$table = FLEA::getSingleton('Table_ModaUser');
			$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");	
				$pagesize	=	24;
				$conditions	=	"pass = 1";
				$sortby		=	"liulanliang DESC";
				if($_COOKIE['moda_version'] == 'version_2'){
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $table, $pagesize, $conditions , $sortby );
				}
				elseif($_COOKIE['moda_version'] == 'version_1'){
					FLEA::loadClass('Lib_ModaPager');
					$page	=	new Lib_ModaPager( $table, $pagesize, $conditions , $sortby );
				}
				else{
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $table, $pagesize, $conditions , $sortby );
				}
				
				$rowset	=	$page->rowset;
				
			//include(APP_DIR."/View/ShowModaer.html");
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/ShowModaer.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/View/ShowModaer.html");
			else
				include(APP_DIR."/version_2/ShowModaer.html");
		}
	
		
		function actionShowMore(){
			$table = FLEA::getSingleton('Table_ModaUser');	
			$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");		
				$pagesize	=	24;
				$conditions	=	"pass = 1";
				$sortby		=	"showcount desc";
				if($_COOKIE['moda_version'] == 'version_2'){
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $table, $pagesize, $conditions , $sortby );
				}
				elseif($_COOKIE['moda_version'] == 'version_1'){
					FLEA::loadClass('Lib_ModaPager');
					$page	=	new Lib_ModaPager( $table, $pagesize, $conditions , $sortby );
				}
				else{
					FLEA::loadClass('Lib_ModaPager2');
					$page	=	new Lib_ModaPager2( $table, $pagesize, $conditions , $sortby );
				}				
				$rowset	=	$page->rowset;
				
			//include(APP_DIR."/View/ShowModaer.html");
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/ShowModaer.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/View/ShowModaer.html");
			else
				include(APP_DIR."/version_2/ShowModaer.html");
		}
		/*����mianչʾ		
		*/	
	   function actionPersonMainShow()
	   {
	   		if(!$this->_CheckAdminRoot())
			{
				$this->_alert('��û��Ȩ��',"?controller=ModaIndex");
				exit;
			}
			
	   
	   		if($_POST['show_id'] == "")
			{
				$this->_alert('����showID��',"?controller=ModaIndex");
				exit;
			}
	   		if($_POST['user_id'] == "")
			{
				$this->_alert('����',"?controller=ModaIndex");
				exit;
			}
	   		$table = FLEA::getSingleton('Table_ModaShows');	
			$user_id = (int)$_POST['user_id'];
			$table->CansleMainShow($user_id);
			
			$data['show_id'] = (int)$_POST['show_id'];
			$data['main'] = 1;
			$table->updatetar($data);
			FI_changePage("?controller=ModaIndex&action=Person&id=".(int)$_POST['user_id']."");
		}
		
		
	  //��ϸ��̸
	 function actionModaNews()
	 {
			$table = FLEA::getSingleton('Table_ModaNews');
			$usertable = FLEA::getSingleton('Table_ModaUser');
			if(!(int)$_GET['id']){
				$this->_alert('�Ƿ�����',"?controller=ModaIndex");
			}
			$result = $usertable->find("username='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");
			$news = $table->find("news_id = '".(int)$_GET['id']."'");
			if(!$news){
				$this->_alert('�Ƿ�����',"?controller=ModaIndex");
			}
			
			$allnews= $table->findAll("","news_id desc limit 8");
			//include(APP_DIR."/View/mdft222.html");
			//moda �汾�л�
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/mdft222.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/View/mdft222.html");
			else
				include(APP_DIR."/version_2/mdft222.html");
	 }
			 
	//��̸�б�
	function actionAllNews()
	{
			$table = FLEA::getSingleton('Table_ModaNews');
			$result = $table->findAll("","news_id desc limit 4");
			$result1 = $table->findAll("","news_id desc limit 4,100");
			$result2 = $table->findAll("","news_id desc");
			//include(APP_DIR."/View/ftlb.html");
			//moda �汾�л�
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/ftlb.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/View/ftlb.html");
			else
				include(APP_DIR."/version_2/ftlb.html");
	} 
		
	//ͷ��
	function actionUploadPic(){
	
	
	
	
		if($_POST['upload']=='upload'){
			
			session_start(); 
			$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
			$big_img=$_SESSION['random_key'];
			
			$file_name =  $_FILES['file']['name'];
			$file_postfix = substr($file_name,strrpos($file_name,"."));
			
			$uploaddir ='uploadd/';
			$uploadfile = $uploaddir .$big_img .file_postfix;

			
			$_SESSION["fullname"]= "face".file_postfix;
			
			$_FILES['file']['name']=strtolower($_FILES['file']['name']);
			$tmp_filename=explode(".",$uploadfile);
			
			

			$limitType	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');

			
			if(in_array($file_postfix,$limitType))
			{
				//$up_filename=$updir.md5($_FILES['file']['name'].$_FILES['file']['size']).".".$tmp_filename[(count($tmp_filename)-1)] ;	
				
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
						list($w, $h, $type, $attr)=getimagesize($uploadfile);
						
						
						if($w>580 || $h>300){
							$this->_alert('Ԥ��ͼƬӦС��580*300');
							unlink($uploadfile);
							exit;			
						}
					
						
						
						$str='';
						
						if($w>550){
							$str="width:550px;";
						}
						if($h>550){
							$str.="  height:550px;";
						}
						
						$str=empty($str)?'':"style=' ".$str." '";
						$f1="<img src='$uploadfile' border=0 $str id='cropbox' >";
						$f2="<img src='$uploadfile' border=0  $str id='preview' >";
		
						$table=FLEA::getSingleton('Table_ModaUser');
						
						$result=$table->find("username='".$_SESSION["Zend_Auth"]['storage']->username."'");
						if($result['big_head_img']){
							unlink($result['big_head_img']);
						}
						$result['big_head_img'] = $uploadfile;
						$table->update($result);
						
		
						
						$jsstr	=	 '<script language="javascript">parent.$("#showBig").html("'.$f1.'");parent.$("#showThumb").html("'.$f2.'");parent.goss();parent.$("#bigwidth").val("'.$w.'");parent.$("#bigheight").val("'.$h.'");parent.$("#bigImage").val("'.$uploadfile.'");</script>';
							
					}else {
						$jsstr	= "<script>alert('�ļ��ϴ�ʧ��!');</script>";
						}
			
			//
				}else{
					
					echo "<script>alert('���ϴ�ͼƬ!');location.href='?controller=ModaIndex&action=UploadPic'</script>";exit;
				}
			
			include(APP_DIR . "/View/uploadPhoto.html");
		}else{
			include(APP_DIR . "/View/uploadPhoto.html");	
		}
			
		
	}
	
	function actionSaveThumb(){
	
	
	
		session_start(); 
		$visitUser_id = (int)$_POST['user_id'];		
		
		$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));			
		
		$thumb_image_name =$_SESSION['random_key'];
		$path='thumb_image/'.$thumb_image_name.$_SESSION["fullname"];		
		
		if($this->_isPost()){
			$targ_w = $targ_h = 138;
			$jpeg_quality = 100;
			$src = $_POST['bigImage'];
			$img_r = imagecreatefromjpeg($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );

			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);

			//header('Content-type: image/jpeg');
			imagejpeg($dst_r,$path,$jpeg_quality);

			$table=FLEA::getSingleton('Table_ModaUser');
			
			
			/*
				����Ա�û�
			*/

			if($this->_CheckAdminRoot()){
				$result = $table->getUserByUserId($visitUser_id);
				//$result=$table->find("username='".$dataone['username']."'");

			}else{
				$result=$table->find("username='".$_SESSION["Zend_Auth"]['storage']->username."'");
			}
			if($result['head_img'] && $result['head_img']!="thumb_image/head.jpg"){
				unlink($result['head_img']);			
			}
			$result['head_img']=$path;			
			
			//dump($result);			
			$table->update($result);
			
			/*չʾ����ͷ������
			*/
			$disD['uname'] = $result['username'];
			$disD['head_img'] = $result['head_img'];
			//$dusD['create_on'] = 
			
			$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
			$tableModaShowDiscuss->uploadByUname($disD);
				
			
			unset($_SESSION["fullname"]);
			
			redirect("?Controller=ModaIndex&action=GeRenZiLiao&id=".$visitUser_id."");
			
			
			exit;
		}else{
			include(APP_DIR . "/View/saveThumb.html");
		  }
	
	}

	
	
	
	
	//�ӿǺ���
	function _convertSpecialChars($str)
	{
		
					$str	=	trim($str);
					  if(empty($str)) return;
					  if($str=="") return $str;
				
						$str=str_replace("\\","\\\\",$str);//���ȹ���
						
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
						
//					  $str=str_replace("select","\se\le\ct",$str);
//					  $str=str_replace("join","\jo\in",$str);
//					  $str=str_replace("union","\un\ion",$str);
//					  $str=str_replace("where","\wh\ere",$str);
//					  $str=str_replace("insert","\in\se\rt",$str);
//					  $str=str_replace("delete","\de\le\te",$str);
//					  $str=str_replace("update","\up\da\te",$str);
//					  $str=str_replace("like","\li\ke",$str);
//					  $str=str_replace("drop","\dr\op",$str);
//					  $str=str_replace("create","\cr\ea\te",$str);
//					  $str=str_replace("modify","\mo\di\fy",$str);
//					  $str=str_replace("rename","\re\na\me",$str);
//					  $str=str_replace("alter","\al\ter",$str);
//					  $str=str_replace("cast","\ca\st",$str);
						$str = htmlspecialchars($str);
					  
					  return $str;
				
					
	
	}


/*
����û�Ȩ��
*/
	function _getUserAmin($user_id = ''){//�����û�ID�뵱ǰ�û��Ա�
			
			$userName = $_SESSION["Zend_Auth"]['storage']->username;
			//����Ƿ��¼
			if($userName==''){
				//$this->_alert("��û�е�¼��","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));exit();
				
				
				return false;
				
			}
			else
			{	
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				//���ݴ���ID�뵱ǰ�û��Աȣ��Ʋ�չʾ����
				if($user_id)
				{
					
					/*
						����ID���û���
					*/
					
					$ShowUser = $tableModaUser->getUserByUserId($user_id);
					
			
					if($userName == $ShowUser['username'])
					{
						return true;
					}
					else
						{
							//$this->_alert("�Ƿ�������","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));exit();
							return false;
						}
				}
				else//����Ƿ������û�
				{
					
					$modaUser = $tableModaUser->getUserByUnamePass($userName);
					//dump($modaUser);
					
					if($modaUser!='')
					{
						return true;
					}
					else
					{
						//$this->_alert("�Ƿ�������","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));exit();
						return false;
					}
				
				}
				
			
			}

	}	
/*
	��õ�ǰ�û�ID
*/
function _GetSessionUserId()
{
	
		$curUsername = $_SESSION["Zend_Auth"]['storage']->username;
		
		/*
			��ȡmoda_user
		*/
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$userDat = $tableModaUser->getUserByUname($curUsername);
		
		return $userDat['user_id'];
	
	
}

	function actionSearchModa()
	{
		
				$tableshowips = FLEA::getSingleton('Table_ModaShowIps');
				$suc = $tableshowips->forSearch();
				if(!$suc)
				{
						$this->_alert("����Ƶ��������","/");
						exit();
				}
				/*
				*/
				$nickname =	$this->_convertSpecialChars($_POST['nickname']);
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				
				$pagesize	=	20;
				$conditions	=	"nickname Like '%".$nickname."%' And pass = 1";
				$sortby		=	"";
				FLEA::loadClass('Lib_ModaPager');
				$page	=	new Lib_ModaPager( $tableModaUser, $pagesize, $conditions , $sortby );
				$rowset	=	$page->rowset;
				
				$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
				$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
				$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
				$rankshow = $tableModaRankShow->getShowByMingci();
				foreach($rankshow as $st)//��ѭ��
				{
							$showD = $tableModaShow->getShowByShowId($st['show_id']);
							$temp = 0;
							foreach($rowset as $rt)//�û�ѭ��
							{
										if( $rt['user_id'] == $st['user_id'] )
										{
											$rankD = $tableModaRanks->getRankById($st['rank_id']);
											if($st['mingci'] == 1)
												$rowset[$temp]['mingci_img'] = $rankD['first_mark'];
											if($st['mingci'] == 2)
												$rowset[$temp]['mingci_img'] = $rankD['sec_mark'];
											if($st['mingci'] == 3)
												$rowset[$temp]['mingci_img'] = $rankD['thr_mark'];
										}
										$temp+=1;
							}
				}	
				include(APP_DIR . "/View/searchModaer.html");
	}

	/*
	����Ա�û�
*/
function _CheckAdminRoot()
{

		if($_SESSION["Zend_Auth"]['storage']->username == 'moda' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='aaa' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='NewYouth' )
			return true;
		if($_SESSION["Zend_Auth"]['storage']->username =='����زز����' )
			return true;
		else
			return false;		
	
}

	function _changePage($url="",$target="self"){
		if($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script type=\"text/javascript\" >$target.location='$url';</script>
</head><body></body></html>";
	}
	
	function _alertLocal($word=""){
		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');</script>
</head><body></body></html>";
	}	
	
	
	
	function actionModaTB(){
		
		
		FI_changePage("?controller=ShowIndex&action=ModaClubAllList");
		
		
		$table = FLEA::getSingleton('Table_ModaUser');
		$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");
		$tablecb = FLEA::getSingleton('Table_ModaClub');
		
		
		$pagesize	=	25;
				$conditions	=	"pass = 1";
				$sortby		=	"user_id DESC";
				//FLEA::loadClass('Lib_Pager');
				FLEA::loadClass('Lib_ModaPager');
				
				//$page	=	new Lib_Pager( $tableModaShow, $pagesize, $conditions , $sortby );
				$page	=	new Lib_ModaPager( $table, $pagesize, $conditions , $sortby );
				
				$rowset	=	$page->rowset;
		
		include(APP_DIR . "/View/mdtb.html");
	
	}
	
	
	function actionLiuLanMoret(){
			$table = FLEA::getSingleton('Table_ModaUser');
			$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");	
			$tablecb = FLEA::getSingleton('Table_ModaClub');
				$pagesize	=	25;
				$conditions	=	"pass = 1";
				$sortby		=	"liulanliang DESC";
				//FLEA::loadClass('Lib_Pager');
				FLEA::loadClass('Lib_ModaPager');
				
				//$page	=	new Lib_Pager( $tableModaShow, $pagesize, $conditions , $sortby );
				$page	=	new Lib_ModaPager( $table, $pagesize, $conditions , $sortby );
				
				$rowset	=	$page->rowset;
				
			include(APP_DIR."/View/mdtb.html");
		}
	
		
		function actionShowMoret(){
			$table = FLEA::getSingleton('Table_ModaUser');		
			$resultuser = $table->find("username ='".$_SESSION["Zend_Auth"]['storage']->username."' And pass = 1");	
			$tablecb = FLEA::getSingleton('Table_ModaClub');
				$pagesize	=	25;
				$conditions	=	"pass = 1";
				$sortby		=	"showcount desc";
				//FLEA::loadClass('Lib_Pager');
				FLEA::loadClass('Lib_ModaPager');				
				//$page	=	new Lib_Pager( $tableModaShow, $pagesize, $conditions , $sortby );
				$page	=	new Lib_ModaPager( $table, $pagesize, $conditions , $sortby);				
				$rowset	=	$page->rowset;
				
			include(APP_DIR."/View/mdtb.html");
	}
	
	
	function cut_str($sourcestr,$cutlength) 
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
          $returnstr = $returnstr . "...";//��������ʱ��β������ʡ�Ժ�
      }
    return $returnstr; 

}
	
	
	
	
	function actionXgirl()
	{
		include(APP_DIR.'/tow_view/X-Girl.html');		
	}	
	
	
	function actionRecruit()
	{
		$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
		$modaEvent  = $tableModaEvent->findAll("channeltype = 1","event_id desc");
		//include(APP_DIR."/View/modaEventList.html");
		//dump($modaEvent);
	//	include(APP_DIR.'/tow_view/Recruit.html');	
	//  �汾�л�-by zh
		if($_COOKIE['moda_version'] == 'version_2')
			include(APP_DIR."/".$_COOKIE['moda_version']."/Recruit.html");
		elseif($_COOKIE['moda_version'] == 'version_1')
			include(APP_DIR."/tow_view/Recruit.html");
		else
			include(APP_DIR."/version_2/Recruit.html");
	}	
	
	function actionRecruitpage()
	{
			$event_id = (int)$_GET['event_id'];
			if(!$event_id)
				FI_alert("������","?Controller=ModaIndex");
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$modaEvent  = $tableModaEvent->find("event_id =".$event_id."");
			if(!$event_id)
				FI_alert("������!","?Controller=ModaIndex");
		//	include(APP_DIR.'/tow_view/Recruit_in.html');
		//  �汾�л�-by zh
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/Recruit_in.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/Recruit_in.html");
			else
				include(APP_DIR."/version_2/Recruit_in.html");
					
	}	
	
	
	function actionXgirllist()
	{
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$modaEvent  = $tableModaEvent->findAll("channeltype = 2","event_id desc");
			
		//	include(APP_DIR.'/tow_view/xgirllist.html');
		//  �汾�л�-by zh
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/xgirllist.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/xgirllist.html");
			else
				include(APP_DIR."/version_2/xgirllist.html");
	}	
	
	
	function actionCooperations()
	{
//			$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
//			$pagesize	=	25;
//			$conditions	=	"pass = 11";
//			$sortby		=	"";
//			FLEA::loadClass('Lib_ModaPager');				
//			$page	=	new Lib_ModaPager( $Table_ModaUser, $pagesize, $conditions , $sortby);				
//			$rowset	=	$page->rowset;
			
		//	include(APP_DIR.'/tow_view/cooperations.html');	
		//  �汾�л�-by zh
			if($_COOKIE['moda_version'] == 'version_2')
				include(APP_DIR."/".$_COOKIE['moda_version']."/cooperations.html");
			elseif($_COOKIE['moda_version'] == 'version_1')
				include(APP_DIR."/tow_view/cooperations.html");
			else
				include(APP_DIR."/version_2/cooperations.html");
	}	
	
	
	function actionModapage1()
	{
//			$Table_ModaUser = FLEA::getSingleton('Table_ModaUser');
//			$pagesize	=	25;
//			$conditions	=	"pass = 11";
//			$sortby		=	"";
//			FLEA::loadClass('Lib_ModaPager');				
//			$page	=	new Lib_ModaPager( $Table_ModaUser, $pagesize, $conditions , $sortby);				
//			$rowset	=	$page->rowset;
			
			include(APP_DIR.'/tow_view/modapage1.html');		
	}	
	
	
	
	
	
		//�汾�л�
	 function actionBanbenqiehuan()
	 {
		if($_GET['ver'] == 2)
			setcookie('moda_version','version_2',time()+3600*24 *365);
		if($_GET['ver'] == 1)
			setcookie('moda_version','version_1',time()+3600*24 *365);	
		FI_changePage('/');
	}
	
	

}
?>
