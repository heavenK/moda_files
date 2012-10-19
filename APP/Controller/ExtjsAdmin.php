<?php
/* 后台管理员，以及主持人Control层代码*/
include 'Fun_Include.php';

FLEA::loadClass('Controller_BoBase');
class Controller_ExtjsAdmin extends Controller_BoBase
{
	
    function Controller_ExtjsAdmin() {
		parent::Controller_BoBase();
		
		/*口令错误
		*/
		if($_SESSION["modaAdmin"]->modaAdmin != 1)
		{
				include('./extjsAdmin/login.html');	
				exit;
		}
		/*管理员
		*/
//		if($_SESSION["Zend_Auth"]['storage']->adminid!=1){
//				
//				if($_SESSION["Zend_Auth"]['storage']->username!='moda' && $_SESSION["Zend_Auth"]['storage']->username!='aaa')
//				{
//						$this->_alert("权限不够，您可能没有登录！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/?Controller=ExtjsAdmin"));
//						exit();
//					
//				}
//		}
		
		if(!FI_CheckAdminRoot())
		{
			FI_alert("权限不够，您可能没有登录！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/?Controller=ExtjsAdmin"));
		}
		

    }
	


	function actionIndex(){
		include('./extjsAdmin/index.html');
	}	
	
	
	/*登录验证
	*/
	function actionAdminLogin(){
		$_SESSION["Zend_Auth"]['storage']->modaAdmin = 1;
		echo "true";
	}
	
	/*操作界面
	*/
	function actionMainView(){
		include('./extjsAdmin/index.html');
	}	


	/*添加美达榜
	*/
	function actionAddModaRank(){
		$arr = array(
			'rank_title' => $_POST["rank_title"],
			'overtime'	=>  $_POST["overtime"],
			'content'	=>  $_POST["content"]
		);	
	
		if($_POST["rank_id"] != "")
			$arr['rank_id'] = $_POST["rank_id"];

		$table = FLEA::getSingleton('Table_ModaRanks');
		$has = $table->getRankByTitle($arr['rank_title']);
		
		if($has)
		{
			if($_POST["rank_id"] == "")
			{
					echo "{success:false,message:\"标题名已经存在，请换一个再试\"}";
					exit;
				}
		}
		
		$rrr = $table->save($arr);	
		if($rrr)
		{
				echo "{success:true,message:\"成功\"}";
			}
		else
			echo "{success:false,message:\"添加失败\"}";
		
		exit;
	
	}



	function actionimglist(){
		$user_id = (int)$_GET['user_id'];
		
		$this->connect_to19_db();
		FLEA::loadClass('Services_json');

		$sql = "select img1,img2,img3,img4,img5,img6 from moda_users where user_id = ".$user_id;
		if($result = mysql_query($sql))
		{
			$json = new Services_JSON();
			$items = array();
			$row = mysql_fetch_assoc($result);
			$success = '{"images":[{"name":"'.$row[img1].'","url":"'.$row[img1].'"},{"name":"'.$row[img2].'","url":"'.$row[img2].'"},{"name":"'.$row[img3].'","url":"'.$row[img3].'"},{"name":"'.$row[img4].'","url":"'.$row[img4].'"},{"name":"'.$row[img5].'","url":"'.$row[img5].'"},{"name":"'.$row[img6].'","url":"'.$row[img6].'"}]}';
			
		}
		echo $success;

	}
	
	
	function actionUserInfo(){
						
				$this->connect_to19_db();
				
				$tableModaShow = FLEA::getSingleton('Table_ModaShows');		
				$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
				$tableModaShowDiscuss = FLEA::getSingleton('Table_ModaShowDiscuss');
				$tableModaClub = FLEA::getSingleton('Table_ModaClub');
				$tableModaClubCall = FLEA::getSingleton('Table_ModaClubCall');
				
				
				$count=@mysql_query("select count(user_id) as count from moda_users where pass = 1");	
				$count_nu=@mysql_fetch_array($count);
				$count_number=$count_nu['count'];
				
				$limit=$_POST['limit'];
				$start=$_POST['start'];

				if($start == "")
				{
					$start =0;
				}
				if($limit == "")
				{
					$limit =10;
				}
				$i=0;
				if($start+$limit>$count_number){
					$limit=$limit-($start+$limit-$count_number);
				}
				
				
				$req=@mysql_query("select * from moda_users where pass = 1 order by passtime desc , user_id DESC limit $start,$limit");
				
				$strs = "{'totalCount':'$count_number','rows':[";
															   
				while($return=@mysql_fetch_array($req))
				{
					$user_id=$return['user_id'];
					if($return['passtime'])
					$passtime=date("Y-m-d",$return['passtime']);
					else
					$passtime = '无';
					$restime=date("Y-m-d",$return['restime']);
					$username=$return['username'];
					$truename=$return['truename'];

					/*通行证
					*/
					$pasuser = @mysql_query("select lastvisit from passport_user where username =  $username");
					$pasinfo=@mysql_fetch_array($pasuser);
					$lastvisit = date("Y-m-d",$pasinfo['lastvisit']);
					if($pasinfo['lastvisit'])
					$lastvisit = date("Y-m-d",$pasinfo['lastvisit']);
					else
					$lastvisit = '无';
					/*展示
					*/
					
					$reallpageview = 0;
					$att_count = 0;
					$dis_count = 0;
					$club_count = 0;
					$clubcall_count = 0;
					
					$shows = $tableModaShow->getViewsByUsername($username);
					foreach($shows as $srows)
					{
							//页面浏览量
							$reallpageview+=$srows['views'];
							/*图片类型附件量
							*/
							$attcount = $tableModaAttach->getPicTypeCountByShowId($srows['show_id']);
							$att_count += $attcount;
							/*图片评论量
							*/
							$discount = $tableModaShowDiscuss->getDiscussCountByShowId($srows['show_id']);
							$dis_count += $discount;
						}
					//展示数
					$showcount = $tableModaShow->getViewCountByUsername($username);
					/*贴吧主题量
					*/
					$clubcount = $tableModaClub->getClubCountByUid($user_id);
					$club_count += $clubcount;
					/*贴吧回复数
					*/
					$clubcall_count = $tableModaClubCall->getClubCallCountByChairid($user_id);
					/*前置展示封面
					*/
					$mainshow = $tableModaShow->getMainShowById($username);
					$showurl = $mainshow['show_img'];
					$show_id = $mainshow['show_id'];
					/*显示浏览量
					*/
					$pageviews=$return['liulanliang'];	
					
					
					$username=$return['username'];	
					$nickname=$return['nickname'];	
					$birthdate=$return['birthdate'];	
					$mobile=$return['mobile'];
					$qq=$return['qq'];
					$height=$return['height'];
					$weight=$return['weight'];
					$email=$return['email'];
					
					$strs.=	"{'user_id':'$user_id','truename':'$truename','passtime':'$passtime','restime':'$restime','lastvisit':'$lastvisit','reallpageview':'$reallpageview','pageviews':'$pageviews','showcount':'$showcount','att_count':'$att_count','dis_count':'$dis_count','club_count':'$club_count','clubcall_count':'$clubcall_count','showurl':'$showurl'";

					$strs.=",'username':'$username','nickname':'$nickname','birthdate':'$birthdate','mobile':'$mobile','qq':'$qq','height':'$height','weight':'$weight','show_id':'$show_id','email':'$email'}";
					
					$i++;
					if($i<$limit){$strs.= ",";}				
					
				}
					
					$strs.= "]}";
					echo $strs;
		
	}
		
	function actionNewUserList(){
	
		$search=$_POST['title'];
		$search=iconv("UTF-8","gbk",$search);
		/*翻页
		*/
		$limit=$_POST['limit'];
		$start=$_POST['start'];
		if($start == "")
		{
			$start =0;
		}
		if($limit == "")
		{
			$limit =10;
		}
		FLEA::loadClass('Services_Db');
		$db=new Services_Db(DBIP,DBUSER,DBPW,DBNAME);
		//echo $db->toExtJson('moda_users',$start,$limit,"pass = 0");
		
		if(!$search)
		{
				$sqltocount = 'select * from moda_users WHERE pass = 0';
				$sqltoquery = 'select * from moda_users WHERE pass = 0 order by user_id DESC limit '.$start.','.$limit;
			}
		else
		{
				$sqltocount = "select * from moda_users WHERE username like '%$search%'";
				$sqltoquery = "select * from moda_users WHERE username like '%$search%' order by user_id DESC limit ".$start.','.$limit;
				//echo $sqltocount;
			}
		
	
		echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
		
	}		



	function actionGetListNopass(){
	
		
		/*翻页
		*/
		$limit=$_POST['limit'];
		$start=$_POST['start'];
		if($start == "")
		{
			$start =0;
		}
		if($limit == "")
		{
			$limit =10;
		}
		FLEA::loadClass('Services_Db');
		$db=new Services_Db(DBIP,DBUSER,DBPW,DBNAME);
		echo $db->toExtJson('moda_users',$start,$limit,"pass = 2");
		
		
		
	}		
	
	
	
	function actionGetModaTopGirlList(){
		
		/*翻页
		*/
		$limit=$_POST['limit'];
		$start=$_POST['start'];
		if($start == "")
		{
			$start =0;
		}
		if($limit == "")
		{
			$limit =10;
		}
		FLEA::loadClass('Services_Db');
		$db=new Services_Db(DBIP,DBUSER,DBPW,DBNAME);
		//echo $db->toExtJson('moda_ranks',$start,$limit);

		$sqltocount = 'select * from moda_ranks';
		$sqltoquery = 'select * from moda_ranks order by rank_id DESC limit '.$start.','.$limit;
	
		echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
			
					
	}
	
	
	
	
	function actionRankShowManange()
	{
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$tableModaUser = FLEA::getSingleton('Table_ModaUser');
			$tableModaShow = FLEA::getSingleton('Table_ModaShows');		
			
			$rank_id = $_GET['rank_id'];	
			$limit=$_POST['limit'];
			$start=$_POST['start'];

			if($start == "")
			{
				$start =0;
			}
			if($limit == "")
			{
				$limit =10;
			}
		
			$rankshows = $tableModaRankShow->findAll(" rank_id = $rank_id ORDER by Id DESC limit $start,$limit");
			
			$count_number = $tableModaRankShow->findCount("rank_id = $rank_id ");
			
			$i = 0;
			$strs = "{'totalCount':'$count_number','rows':[";
														   
			foreach($rankshows as $row)
			{
					
					$perinfo = $tableModaUser->getUserByUserId($row['user_id']);
					$truename = $perinfo['truename'];
					$username = $perinfo['username'];
					$show = $tableModaShow->getShowByShowIdNoLimit($row['show_id']);
					$views = $show['views'];
					
					
					$strs.=	"{'Id':'$row[Id]','rank_id':'$row[rank_id]','user_id':'$row[user_id]','show_id':'$row[show_id]','title':'$row[title]','head_img':'$row[head_img]','ticket':'$row[ticket]','mingci':'$row[mingci]','truename':'$truename','username':'$username','views':'$views'}";
					
					$i++;
					if($i<$limit){$strs.= ",";}				
				
				}
			$strs.= "]}";
			
			
			echo $strs;
					
	}
		
	/*美达访谈
	*/	
	function actionModaTalkList(){
		
		/*翻页
		*/
		$limit=$_POST['limit'];
		$start=$_POST['start'];
		if($start == "")
		{
			$start =0;
		}
		if($limit == "")
		{
			$limit =10;
		}
		FLEA::loadClass('Services_Db');
		$db=new Services_Db(DBIP,DBUSER,DBPW,DBNAME);
		//echo $db->toExtJson('moda_news',$start,$limit);
		
		
		
		$sqltocount = 'select * from moda_news';
		$sqltoquery = 'select news_id,news_st,news_title,created_on,url,img_url from moda_news order by news_id	DESC  limit '.$start.','.$limit;
	
		echo $db->toExtJsonByGp($sqltocount,$sqltoquery);

					
					
	}
		
	/*美达活动
	*/	
	function actionModaEventList(){
		
		/*翻页
		*/
		$limit=$_POST['limit'];
		$start=$_POST['start'];
		if($start == "")
		{
			$start =0;
		}
		if($limit == "")
		{
			$limit =10;
		}
		FLEA::loadClass('Services_Db');
		$db=new Services_Db(DBIP,DBUSER,DBPW,DBNAME);
		//echo $db->toExtJson('moda_news',$start,$limit);
		
		$sqltocount = 'select * from moda_event';
		$sqltoquery = 'select event_id,home_img,index_img,header_img,title,dateline from moda_event ORDER by event_id DESC limit '.$start.','.$limit;
	
		echo $db->toExtJsonByGp($sqltocount,$sqltoquery);

					
					
	}
	
	
	/*动作，添加美达用户
	*/	
	function actionAddModaUserByAdmin()
	{
				$userD['username'] = iconv("UTF-8","gbk",$_POST['username']);
				$userD['truename'] = iconv("UTF-8","gbk",$_POST['truename']);
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				$tablePassportUser = FLEA::getSingleton('Table_PassportUser');
				$userDat = $tablePassportUser->find("username = '".$userD['username']."'");
				if(!$userDat)
				{
						echo "{success:false,message:\"user no exist\"}"; 
						return false;
				}
				else
				{	
						$userD['nickname'] = $userDat['nickname'];
						$userD['pass'] = 0;
						$userD['restime'] = time();
						$userDat = $tableModaUser->getUserByUnameNoLimit($userD['username']);
						if($userDat)
						{
							echo "{success:false,message:\"moda user is exist \"}"; 
							return false;
						}
						else
						{
							$user_id = $tableModaUser->create($userD);
						}
				}
				
				if(!$user_id)
				{
					echo "{success:false,message:\"这个用户不存在,或已经是MODA会员\"}"; 
				}
				else
				{
					echo "{success:true,message:\"成功\"}"; 
				}		

	}
	
	
	/*动作，美达用户信息修改
	*/	
	function actionModaerInfoEdit(){
		
				$userD['user_id'] = $_POST['user_id'];
				$userD['truename'] = iconv("UTF-8","gbk",$_POST['truename']);
				
				$userD['pageviews'] = $_POST['pageviews'];
				$show_id = $_POST['show_id'];
				
				/*读取moda_user
				*/
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				$userinfo = $tableModaUser->getUserByUserId($userD['user_id']);
				if(!$userinfo)
				{
					echo "{success:false,message:\"修改失败,错误阶段0\"}"; 
					exit;
				}
				
				$suc = $tableModaUser->update($userD);
				
				if(!$suc)
				{
					echo "{success:false,message:\"修改失败,错误阶段1\"}";
					exit;
				}
				
				$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
				$show = $tableModaShow->getShowByShowIdNoLimit($show_id);
				
				if($show['user_id'] != $userD['user_id'])
				{
					echo "{success:false,message:\"错误信息：修改前置展示失败,展示不属于该用户\"}";
					exit;
				}
				
				$tableModaShow->CansleMainShow($userD['user_id']);
				$data['show_id'] = $show_id;
				$data['main'] = 1;
				$suc = $tableModaShow->updatetar($data);
				
				if(!$suc)
				{
					echo "{success:false,message:\"修改失败,错误阶段3\"}"; 
					exit;

				}
				else
					echo "{success:true,message:\"成功\"}"; 
	}
	
	
	
	
	/*动作，美达用户私人信息修改
	*/	
	function actionModaerPerInfoEdit(){
				$userD['user_id'] = $_POST['user_id'];
				$userD['truename'] = iconv("UTF-8","gbk",$_POST['truename']);
				$userD['mobile'] = $_POST['mobile'];
				$userD['qq'] = $_POST['qq'];
				$userD['email'] = $_POST['email'];
				$userD['height'] = $_POST['height'];
				$userD['weight'] = $_POST['weight'];
				
				$tableModaUser = FLEA::getSingleton('Table_ModaUser');
				$userinfo = $tableModaUser->getUserByUserId($userD['user_id']);
				
				if(!$userinfo)
				{
					echo "{success:false,message:\"修改失败,错误阶段0\"}"; 
					exit;
				}
				
				$suc = $tableModaUser->update($userD);
				if(!$suc)
				{
					echo "{success:false,message:\"修改失败,错误阶段1\"}";
					exit;
				}
				else
					echo "{success:true,message:\"成功\"}"; 
	
	}
	
	
	
	
	/*动作，添加展示到美达榜
	*/	
	function actionAddTopGirlShow()
	{
		
					$show_id = (int)$_POST['show_id'];
					$rank_id = $_POST['rank_id'];
					$title = $_POST['title'];
					$title = iconv("UTF-8","gbk",$title);
				
					//查询rank_id
					$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
					$rankDa = $tableModaRanks->getRankById($rank_id);
					
					//dump($rankDa);
					if(!$rankDa)
					{
						//$this->_alert("榜不存在");
						echo "{success:false,message:\"榜不存在,错误阶段0\"}"; 
						exit;
					}
					
					/*读取Table_ModaShows
					*/		
					$tableModaShows = FLEA::getSingleton('Table_ModaShows');
					$showData = $tableModaShows->getShowByShowId($show_id);
					//dump($showData);
					if(!$showData)
					{
						//$this->_alert("展示不存在");
						echo "{success:false,message:\"展示不存在,错误阶段1\"}"; 
						exit;
					}
					
					$RankGirlDat['head_img'] = $showData['show_img'];
					$RankGirlDat['rank_id'] = $rankDa['rank_id'];
					$RankGirlDat['rank_title'] = $rankDa['rank_title'];
					$RankGirlDat['user_id'] = $showData['user_id'];
					$RankGirlDat['show_id'] = $show_id;
					$RankGirlDat['title'] = $title;
					
					//dump($RankGirlDat);
					/*添加展示
					*/
					$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
					$rr = $tableModaRankShow->savetar($RankGirlDat);
					if($rr)
					{
							echo "{success:true,message:\"成功\"}"; 
							exit;
						}
					else
					{
							echo "{success:false,message:\"失败\"}"; 
							exit;
					}	
	
	
	}
	
	/*动作，添加展示到美达榜
	*/	
	function actionTopGirlShowEdit()
	{
					$pd['rank_id'] = $_POST['rank_id'];
					$pd['show_id']  = $_POST['show_id'];
					$pd['ticket']  = $_POST['ticket'];
					//$pd['title']  = iconv("UTF-8","gbk",$_POST['title']);
					$pd['title']  = iconv("UTF-8","gbk",$_POST['title']);
					
					$sd['views']  = $_POST['views'];
					$sd['show_id']  = $_POST['show_id'];
					
					$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
					$suc = $tableModaRankShow->updatetar($pd);
					
					if($suc)
					{
						}
					else
					{
							echo "{success:false,message:\"修改失败：阶段错误0\"}"; 
							exit;
					}
					
					$tableModaShows = FLEA::getSingleton('Table_ModaShows');
					$suc = $tableModaShows->updatetar($sd);
					if($suc)
							echo "{success:true,message:\"成功\"}"; 
					else
							echo "{success:false,message:\"修改失败：阶段错误1\"}"; 
		}
	
	
	
	
	
	
	/*动作
	*/	
	function actionModaerShowManange()
	{		
				$user_id = $_GET['user_id'];

				if($user_id == "")
				{
							echo "{success:false,message:\"错误\"}"; 
							exit;
					}
				
				/*翻页
				*/
				$limit=$_POST['limit'];
				$start=$_POST['start'];
				if($start == "")
				{
					$start =0;
				}
				if($limit == "")
				{
					$limit =10;
				}
				FLEA::loadClass('Services_Db');
				$db=new Services_Db(DBIP,DBUSER,DBPW,DBNAME);
				
				//echo $db->toExtJson('moda_users',$start,$limit,"pass = 2");
			

				$sqltocount = 'select * from moda_shows WHERE available = 1 and user_id = '.$user_id;
				$sqltoquery = 'select * from moda_shows WHERE available = 1 and user_id = '.$user_id.' order by show_id DESC limit '.$start.','.$limit;
				
				echo $db->toExtJsonByGp($sqltocount,$sqltoquery);
				
				
	}
	
	
	/*动作，展示修改
	*/	
	function actionModaerShowEdit()
	{
				$user_id = $_POST['user_id'];
				
				$pd['show_id'] = $_POST['show_id'];
				$pd['title']  = $_POST['title'];
				//$pd['text']  = $_POST['text'];
				$pd['views']  = $_POST['views'];
				
				$pd['public']  = $_POST['public'];
				$pd['main']  = $_POST['main'];
				
				
				$pd['title'] = iconv("UTF-8","gbk",$pd['title']);
				//$pd['text'] = iconv("UTF-8","gbk",$pd['text']);
				
				
				
				$tableModaShows = FLEA::getSingleton('Table_ModaShows');
				
				
				if($_POST['public'] == "")
				{	
						$pd['public'] = 0;
					}
				if($_POST['main'] == "")
				{	
						$pd['main'] = 0;
					}
				else
				{
						$suc = $tableModaShows->CansleMainShow($user_id);	
						
					}
				
				$suc = $tableModaShows->updatetar($pd);
				
				if($suc)
				{
						echo "{success:true,message:\"成功\"}"; 
						exit;
					}
				else
				{
						echo "{success:false,message:\"修改失败：阶段错误1\"}"; 
						exit;
				}
				
			
	}
	
	
	
	
		/*审核通过
		*/
		function actionAgree(){
			
			$user_id = (int)$_POST['user_id'];		
			
			$ck = FLEA::getSingleton('Table_ModaUser');
			
			$result = $ck->find($user_id);
			$result['pass']=1;
			$result['passtime']= time();
			$suc = $ck->update($result);
			if(!$suc)
			{
					//echo "{success:false,message:\"错误：阶段错误0\"}"; 
					echo "错误：阶段错误0";
					exit;
			}
			
			
			/*处理展示
			*/
			$data['user_id'] = (int)$_GET["id"];
			$data['available'] = 1;
			$tableModaShows = FLEA::getSingleton('Table_ModaShows');
			$suc = $tableModaShows->failByUserId($data);
			
			if($suc)
			{
					//echo "{success:true,message:\"成功\"}"; 
					echo "成功";
					exit;
				}
			else
			{
					//echo "{success:false,message:\"修改失败：阶段错误1\"}"; 
					echo "错误：阶段错误1";
					exit;
			}
			
		}
	
		/*待审核操作
		*/
		function actionNoAgree(){
			$ck = FLEA::getSingleton('Table_ModaUser');
			$user_id = (int)$_POST['user_id'];		
			$result = $ck->find($user_id);
			
			//echo $_POST["user_id"];
			
			$result['pass'] = 2;
			$suc = $ck->update($result);
			if(!$suc)
			{
					//echo "{success:false,message:\"错误：阶段错误0\"}"; 
					echo "错误：阶段错误0";
					exit;
			}
			/*处理展示
			*/
			$data['user_id'] = $user_id;
			$data['available'] = 0;
			$tableModaShows = FLEA::getSingleton('Table_ModaShows');
			$suc = $tableModaShows->failByUserId($data);
			if(!$suc)
			{
					//echo "{success:false,message:\"错误：阶段错误1\"}"; 
					echo "错误：阶段错误1";
					exit;
			}
			
			/*美女榜展示处理
			*/
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$suc = $tableModaRankShow->delRankShowByUserId($user_id);
			if($suc)
			{
					//echo "{success:true,message:\"成功\"}";
					echo "成功";
					exit;
				}
			else
			{
					//echo "{success:false,message:\"错误：阶段错误2\"}";
					echo "错误：阶段错误2";
					exit;
			}
			
		}
	
	/*投票排序
	*/	
	function actionSortByTicket(){
			
			
		    $rank_id = (int)$_POST['rank_id'];
			
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$res = $tableModaRankShow->sortByTicket($rank_id);
			
			if(!$res)
			{
					//echo "{success:true,message:\"成功\"}";
					echo "错误：阶段错误0";
					exit;
				}
				elseif($res == "none")
				{
					echo "成功";
					exit;
					}
			$count = 1;
			foreach ($res as $r){
			
						$r['mingci']=$count;
						$count += 1;
						$tableModaRankShow->updatetar($r);
						if(!$res)
						{
								//echo "{success:true,message:\"成功\"}";
								echo "错误：阶段错误".$r['show_id']."排序失败";
								exit;
							}
						
						
			}
			echo "成功";
			exit;
		}	
	
	  /* 取消排序
	  */
	  function actionNOTicketSort(){
		  $rank_id = (int)$_POST['rank_id'];
		  $tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
		  $ranSDat = $tableModaRankShow->NOsort($rank_id);
		  
		  if(!$ranSDat)
		  {
				  echo "错误：阶段错误0";
				  exit;
			  }
			  elseif($res == "none")
			  {
				  echo "成功";
				  exit;
				  }
				  else
				  {
					  echo "成功";
					  exit;
					  }
		  
	  }		
	/* 删除榜
	*/
	function actionRankDel(){
		$rank_id = (int)$_POST['rank_id'];
		
		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
		$suc = $tableModaRankShow->delRankShowByRankId($rank_id);
		if(!$suc)
		{
				echo "错误：阶段错误0";
				exit;
			}
				
		$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
		$suc = $tableModaRanks->delByRankId($rank_id);
		
		
		
		if($suc)
		{
				//echo "{success:true,message:\"成功\"}";
				echo "成功";
				exit;
			}
		else
		{
				//echo "{success:false,message:\"错误：阶段错误2\"}";
				echo "错误：阶段错误1";
				exit;
		}
	
	}	
	/* 榜内展示删除
	*/
	function actionRankShowDel(){
		$rank_id = (int)$_POST['rank_id'];
		$show_id = (int)$_POST['show_id'];
		
		$data['rank_id'] = $rank_id;
		$data['show_id'] = $show_id;
		
		//dump($data);
		
		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
		$suc = $tableModaRankShow->delRankShow($data);
		if($suc)
		{
				//echo "{success:true,message:\"成功\"}";
				echo "成功";
				exit;
			}
		else
		{
				//echo "{success:false,message:\"错误：阶段错误2\"}";
				echo "错误：阶段错误1";
				exit;
		}
	}
	
	/* 删除访谈
	*/
	function actionTalkDel(){
		$news_id = (int)$_POST['news_id'];
		
		$tableModaNews = FLEA::getSingleton('Table_ModaNews');
		$suc = $tableModaNews->delByNewsId($news_id);
		if($suc)
		{
				echo "成功";
				exit;
			}
		else
		{
				echo "错误：阶段错误1";
				exit;
		}
		
	}
	
	
	/*美达活动eventDel
	*/
	function actionEventDel(){
			
			$event_id = (int)$_POST['event_id'];
		
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$suc = $tableModaEvent->delByEventId($event_id);
			if($suc)
			{
					echo "成功";
					exit;
				}
			else
			{
					echo "错误：阶段错误1";
					exit;
			}
			
	}
	
	//访谈
	function actionModaNews(){	
	
		if($this->_isPost()){	
				$table = FLEA::getSingleton('Table_ModaNews');
				$arr=array(
							'news_title'   =>$_POST['news_title'],
							'sortvalue'   =>$_POST['sortvalue'],
							'news_content' =>$_POST['news_content'],
							'news_st' =>$_POST['news_st'],
							'dateline' =>time(),
							'content' =>$_POST['content']
							);
				$suc = $table->save($arr);	
				if($suc)
				{
						echo   '<script>alert("添加成功");window.self.close();</script>';
					}
				else
				{
						$this->_alert("添加失败");
				}
				exit;
		}
		else
		{
				include('./extjsAdmin/htmlviews/modanews.html');
			
			}
		
	}
	/*活动
	*/
	function actionEventAdd()
	{
		
		if($this->_isPost())
		{	
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');

			$arr['content'] = $_POST['content'];
			$arr['title'] = $_POST['title'];
			$arr['channeltype'] = $_POST['channeltype'];
			$arr['link'] = $_POST['link'];
						
			$suc = $tableModaEvent->save($arr);	
			if($suc)
					echo   '<script>alert("添加成功");window.self.close();</script>';
			else
					$this->_alert("添加失败");
			exit;
		}
		
		include('./extjsAdmin/htmlviews/eventadd.html');	
	}
	
	//修改访谈信息
	function actionEditNews(){
		$table = FLEA::getSingleton('Table_ModaNews');
		$news = $table->find("news_id='".(int)$_GET['news_id']."'");
		
		include('./extjsAdmin/htmlviews/editnews.html');	
		
	}
	
	/*美达活动actionEventEditView
	*/
	function actionEventEditView(){
			
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$news = $tableModaEvent->find("event_id='".(int)$_GET['event_id']."'");
			
			include('./extjsAdmin/htmlviews/eventEdit.html');	
	}
	
	/*访谈修改动作
	*/
	function actionEditModaNews()
	{
			$table = FLEA::getSingleton('Table_ModaNews');
			$news  = $table->find("news_id='".(int)$_POST['id']."'");
			$uploaddir ='modanewspic/';	
			$file_name = $_FILES['img_url']['name'];
			$file_postfix = substr($file_name,strrpos($file_name,"."));
			$newname	=	$uploaddir."/".rand(1000,9999).time().$file_postfix;
			
			$file_name1 = $_FILES['url']['name'];
			$file_postfix1 = substr($file_name1,strrpos($file_name1,"."));
			$newname1	=	$uploaddir."/".rand(1000,9999).time().$file_postfix1;
			
			$news['news_title'] = $_POST['news_title'];
			$news['sortvalue'] = $_POST['sortvalue'];
			$news['news_content'] =$_POST['news_content'];
			$news['news_st'] = $_POST["news_st"];
			$news['content'] = $_POST['content'];
			
			if($file_name){
				if($news['img_url']){
					unlink($news['img_url']);
				}
				$news['img_url'] =$newname;
			}
			
			if($file_name1){
				if($news['url']){
					unlink($news['url']);
				}
				$news['url'] =$newname1;
			}
			
			$suc = $table->update($news);
			$result = move_uploaded_file($_FILES['img_url']['tmp_name'],$newname);
			$result1 = move_uploaded_file($_FILES['url']['tmp_name'],$newname1);
			
			if($suc)
			{
					echo   '<script>alert("修改成功");window.self.close();</script>';
				}
			else
			{
					$this->_alert("添加失败");
			}
	
	}
	/*美达活动EventEdit
	*/
	function actionEventEdit()
	{
	
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$news  = $tableModaEvent->find("event_id='".(int)$_POST['id']."'");
			
			$uploaddir ='modanewspic';	
			$file_name = $_FILES['home_img']['name'];
			$file_postfix = substr($file_name,strrpos($file_name,"."));
			$home_img	=	$uploaddir."/modaEvent".rand(1000,9999).time().$file_postfix;
			
			$file_name1 = $_FILES['index_img']['name'];
			$file_postfix1 = substr($file_name1,strrpos($file_name1,"."));
			$index_img	=	$uploaddir."/modaEvent".rand(1000,9999).time().$file_postfix1;
			
			$file_name2 = $_FILES['header_img']['name'];
			$file_postfix2 = substr($file_name2,strrpos($file_name2,"."));
			$header_img	=	$uploaddir."/modaEvent".rand(1000,9999).time().$file_postfix2;
			
			$news['title'] = $_POST['title'];
			$news['channeltype'] = $_POST['channeltype'];
			$news['content'] = $_POST['content'];
			$news['link'] = $_POST['link'];
			
			if($file_name){
				if($news['home_img']){
					unlink($news['home_img']);
				}
				$news['home_img'] =$home_img;
			}
			
			if($file_name1){
				if($news['index_img']){
					unlink($news['index_img']);
				}
				$news['index_img'] =$index_img;
			}
			
			if($file_name2){
				if($news['header_img']){
					unlink($news['header_img']);
				}
				$news['header_img'] =$header_img;
			}
			
			
			$suc = $tableModaEvent->update($news);
			$result = move_uploaded_file($_FILES['home_img']['tmp_name'],$home_img);
			$result1 = move_uploaded_file($_FILES['index_img']['tmp_name'],$index_img);
			$result2 = move_uploaded_file($_FILES['header_img']['tmp_name'],$header_img);
			if($suc)
			{
					echo   '<script>alert("修改成功");window.self.close();</script>';
				}
			else
			{
					$this->_alert("添加失败");
			}
		
	}




	
	
	/*链接数据库函数
	*/
	function connect_to19_db(){
		mysql_connect (DBIP, DBUSER, DBPW) or die ('I cannot connect to mysql because: ' . mysql_error());
		mysql_query ("set names utf8");
		mysql_select_db (DBNAME) or die ('I cannot select the database because: '.mysql_error());
	}
	/*动作跳转
	*/
	function _changePage($url="",$target="self"){
		if($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script type=\"text/javascript\" >$target.location='$url';</script>
</head><body></body></html>";
	}
	
	
	
	
}
?>