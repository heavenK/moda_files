<?php
/* 后台管理员，以及主持人Control层代码*/
FLEA::loadClass('Controller_BoBase');
class Controller_Admin extends Controller_BoBase
{
	
    function Controller_Admin() {
		parent::Controller_BoBase();
		
		//$this->_changePage("?Controller=ExtjsAdmin&action=MainView");exit;
		
		//if($_SESSION["Zend_Auth"]['storage']->adminid!=1 ){
		if($_SESSION["Zend_Auth"]['storage']->username!='aaa' ){
		
			if($_SESSION["Zend_Auth"]['storage']->username!='moda' && $_SESSION["Zend_Auth"]['storage']->username!='aaa'&&$_SESSION["Zend_Auth"]['storage']->username!='susan'&& $_SESSION["Zend_Auth"]['storage']->username!='浮游生物'){
				$this->_alert("权限不够，您可能没有登录！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/index.php?Controller=Admin"));exit();
			}	
		}
	

    }
	
	/*美达活动EventAdd
	*/
	function actionEventAdd(){
			

			if($this->_isPost()){	
				$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
	
				$arr['content'] = $_POST['content'];
				$arr['title'] = $_POST['title'];
				
							
				$tableModaEvent->save($arr);	
			
				$this->_alert("添加成功");
			}
			
			
			include(APP_DIR."/adminView/eventadd.html");
			
	}
	
	
	/*美达活动EventSerch
	*/
	function actionEventSerch(){
			
			
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$pagesize	=	20;
			$conditions	=	"";
			$sortby		=	"event_id desc";
			FLEA::loadClass('Lib_Pager');
			$page	=	new Lib_Pager( $tableModaEvent, $pagesize, $conditions , $sortby );
			$rowset	=	$page->rowset;	
			
			
			
			include(APP_DIR."/adminView/eventSerch.html");
			
	}
	
	/*美达活动actionEventEditView
	*/
	function actionEventEditView(){
			
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$news = $tableModaEvent->find("event_id='".(int)$_GET['id']."'");
			include(APP_DIR."/adminView/eventEdit.html");
			
	}
	
	
	/*美达活动eventDel
	*/
	function actionEventDel(){
			
			$event_id = (int)$_GET['event_id'];
		
			dump($event_id);
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			
			
			$tableModaEvent->delByEventId($event_id);
			
			/*$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
			$tableModaRanks->delByRankId($rank_id);
			
		*/
			$this->_changePage("index.php?controller=Admin&action=EventSerch");
			
	}
	
	
	/*美达活动EventEdit
	*/
	function actionEventEdit(){
	
			$tableModaEvent = FLEA::getSingleton('Table_ModaEvent');
			$news  = $tableModaEvent->find("event_id='".(int)$_POST['id']."'");
			$uploaddir ='modanewspic/';	
			$file_name = $_FILES['home_img']['name'];
			$file_postfix = substr($file_name,strrpos($file_name,"."));
			$home_img	=	$uploaddir."/modaEvent".rand(1000,9999).time().$file_postfix;
			
			$file_name1 = $_FILES['index_img']['name'];
			$file_postfix1 = substr($file_name1,strrpos($file_name1,"."));
			$index_img	=	$uploaddir."/modaEvent".rand(1000,9999).time().$file_postfix1;
			
			$file_name1 = $_FILES['header_img']['name'];
			$file_postfix1 = substr($file_name1,strrpos($file_name1,"."));
			$header_img	=	$uploaddir."/modaEvent".rand(1000,9999).time().$file_postfix1;
			
			
			
			$news['title'] = $_POST['title'];

			$news['content'] = $_POST['content'];
			
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
			
			if($file_name1){
				if($news['header_img']){
					unlink($news['header_img']);
				}
				$news['header_img'] =$header_img;
			}
			
			
			
			$tableModaEvent->update($news);
			$result = move_uploaded_file($_FILES['home_img']['tmp_name'],$home_img);
			$result1 = move_uploaded_file($_FILES['index_img']['tmp_name'],$index_img);
			$result2 = move_uploaded_file($_FILES['header_img']['tmp_name'],$header_img);
			
			$this->_alert("修改成功","?controller=Admin&action=EventSerch");
		
		}
	
	
	
	
	
	
	
	/* 榜内展示管理
	*/
		function actionRankShowManange(){
			
			$rank_id = (int)$_GET['rank_id'];
			
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$ranSDat = $tableModaRankShow->getTopGirlShowById($rank_id);
			
			$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
			$temp = 0;
			foreach($ranSDat as $rd)
			{
				$showDats = $tableModaShow->getShowByShowIdNoLimit($rd['show_id']);
				$ranSDat[$temp]['views'] =  $showDats['views'];
				$temp++;
			}
			
			
			
			//dump($ranSDat);
			
			include(APP_DIR."/adminView/RankShowManage.html");
			
		}
	/* 榜内展示管理
	*/
		function actionNOSort(){
			
			$rank_id = (int)$_GET['rank_id'];
			
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$ranSDat = $tableModaRankShow->NOsort($rank_id);
			
			//dump($ranSDat);
			
			$this->_changePage("index.php?controller=Admin&action=AllRank");
			
		}		


	/* 榜内展示修改
	*/
		function actionRankShowEdit(){
			
			$rank_id = (int)$_GET['rank_id'];
			$show_id = (int)$_GET['show_id'];
			$tableModaShow = FLEA::getSingleton('Table_ModaShows');	
			
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$ranSDat = $tableModaRankShow->getTopGirlShowByShowId($show_id);
			
			
			if($this->_isPost()){	
			
				if($_POST['title'])
				{
					$title = $_POST['title'];
					$ranSDat['title'] = $title;
					}
				if($_POST['ticket']>=0)
				{
					$ticket = $_POST['ticket'];
					$ranSDat['ticket'] = $ticket;
					}
				if($_POST['views'])
				{
					$modashows = $tableModaShow->getShowByShowIdNoLimit($show_id);
					$views = $_POST['views'];
					$showDats['show_id'] = $show_id;
					$showDats['views'] = $views;
					$showDats['created_on'] = $modashows['created_on'];
					$tableModaShow->updatetar($showDats);
					
					}	
					
					
				$tableModaRankShow->updatetar($ranSDat);
				$this->_changePage("index.php?controller=Admin&action=RankShowManange&rank_id=".$ranSDat['rank_id']."");
			}
			//dump($ranSDat);
			
			$showDats = $tableModaShow->getShowByShowIdNoLimit($show_id);
						
			
			include(APP_DIR."/adminView/RankShowEidt.html");
			
		}
	/* 榜内展示删除
	*/
	function actionRankShowDel(){
		$rank_id = (int)$_GET['rank_id'];
		$show_id = (int)$_GET['show_id'];
		
		$data['rank_id'] = $rank_id;
		$data['show_id'] = $show_id;
		
		dump($data);
		
		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
		$tableModaRankShow->delRankShow($data);
	
		$this->_changePage("index.php?controller=Admin&action=RankShowManange&rank_id=".$ranSDat['rank_id']."");
	}
		
	
	/* 删除榜
	*/
	function actionRankDel(){
		$rank_id = (int)$_GET['rank_id'];
		
		
		
		//dump($rank_id);
		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
		$tableModaRankShow->delRankShowByRankId($rank_id);
		
		$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
		$tableModaRanks->delByRankId($rank_id);
		
	
		$this->_changePage("index.php?controller=Admin&action=AllRank");
	}	
	
	
		
	/* 删除访谈
	*/
	function actionTalkDel(){
		$news_id = (int)$_GET['news_id'];
		
		//dump($rank_id);
		$tableModaNews = FLEA::getSingleton('Table_ModaNews');
		
		
		$tableModaNews->delByNewsId($news_id);
		
		/*$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
		$tableModaRanks->delByRankId($rank_id);
		
	*/
		$this->_changePage("index.php?controller=Admin&action=AllNews");
	}
	
	
	
		function actionMingCi(){
			//生成名次
			$rankmes = FLEA::getSingleton('Table_ModaRankMes');
			$resultmes = $rankmes->findAll("rank_id = '".$_GET['id']."'","ticket desc");
			$count = 1;
			foreach ($resultmes as $r){
						$r['mc']=$count;
						$count += 1;
						$rankmes->update($r);
			}
			
			$this->_alert("生成成功");	
		
		}
		
		
	/*投票排序
	*/	
		
	function actionSortByTicket(){
			
			
			$rank_id = (int)$_GET['id'];
			
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$res = $tableModaRankShow->sortByTicket($rank_id);
			//dump($res);
			
			$count = 1;
			foreach ($res as $r){
			
						$r['mingci']=$count;
						$count += 1;
						$tableModaRankShow->updatetar($r);
			}
			$this->_alert("完成排序","index.php?controller=Admin&action=AllRank");
			//$this->_changePage("index.php?controller=Admin&action=AllRank");
		}	
		
		
	/*发布*/	
	function actionPollAdd(){
		if($this->_isPost()){	
			$nianji = FLEA::getSingleton('Table_Polls');			
			$data	=	$_POST;
			$data['created_on']	=	time();
			$data['jiaoshiid']	=	$_SESSION["user"]["jiaoshiid"];
			if($nianji->save($data)){
				$this->_alert("成功添加日志！","index.php?controller=Admin&action=PollList&type=".$_POST['type']);exit;
			}else{
				$this->_alert("添加日志发生错误，请重来！",$_SERVER['HTTP_REFERER']);exit;
			}		
		}else{
			include(APP_DIR."/adminView/PollAdd.html");
		}
	}
	function actionPollList(){
		$nianji = FLEA::getSingleton('Table_Polls');		
		$pagesize	=	20;
		$conditions	=	"`type`=".(int)$_GET["type"];
		$sortby		=	"poll_id DESC";
		FLEA::loadClass('Lib_Pager');
		$page	=	new Lib_Pager( $nianji, $pagesize, $conditions , $sortby );
		$rowset	=	$page->rowset;	
		//dump($rowset);
		include(APP_DIR."/adminView/PollList.html");
	
	}
	function actionPollEdit(){	
		$nianji = FLEA::getSingleton('Table_Polls');
		
		if($this->_isPost()){	
			$nianji->disableLinks();		
			$data	=	$_POST;
			//dump($data);
			if($nianji->save($data)){
				$this->_alert("成功修改！","index.php?controller=Admin&action=PollList");exit;
			}else{
				$this->_alert("修改发生错误，请重来！","index.php?controller=Admin&action=PollList");exit;
			}
		}else{	
			//$table2 = FLEA::getSingleton('Table_Lankinglists');	
//			$table2->disableLinks();
//			$rowset2=	$table2->findAll();
			
			//dump($rowset2);
			
			if(!isset($_GET["id"])){$this->_alert("参数错误！",$_SERVER['HTTP_REFERER']);exit;}
			$nianji = FLEA::getSingleton('Table_Polls');
			$row	=	$nianji->find((int)$_GET["id"]);
			include(APP_DIR."/adminView/PollEdit.html");		
		}	
	}
	function actionPollDel(){
		$nianji = FLEA::getSingleton('Table_Polls');
		if(!isset($_GET["id"])){$this->_alert("参数错误，请重来！","index.php?controller=Admin&action=PollList");exit;}
		if($nianji->removeByPkv((int)$_GET['id'])){
			$this->_alert("成功删除记录！","index.php?controller=Admin&action=PollList");exit;
		}else{
			$this->_alert("删除记录发生错误，请重试！",$_SERVER['HTTP_REFERER']);exit;
		}	
	}
	
		/*发布*/	
	function actionLankinglistAdd(){
		if($this->_isPost()){	
			$nianji = FLEA::getSingleton('Table_Lankinglists');			
			$data	=	$_POST;
			//$data['created_on']	=	time();
			//$data['jiaoshiid']	=	$_SESSION["user"]["jiaoshiid"];
			if($nianji->save($data)){
				$this->_alert("成功添加排行榜！","index.php?controller=Admin&action=LankinglistList");exit;
			}else{
				$this->_alert("添加排行榜发生错误，请重来！",$_SERVER['HTTP_REFERER']);exit;
			}		
		}else{
			include(APP_DIR."/adminView/LankinglistAdd.html");
		}
	}
	function actionLankinglistList(){
		$nianji = FLEA::getSingleton('Table_Lankinglists');	
		$nianji->disableLinks();
		$pagesize	=	20;
		$conditions	=	"";
		$sortby		=	"list_id DESC";
		FLEA::loadClass('Lib_Pager');
		$page	=	new Lib_Pager( $nianji, $pagesize, $conditions , $sortby );
		$rowset	=	$page->rowset;
		include(APP_DIR."/adminView/LankinglistList.html");
	
	}
	function actionLankinglistEdit(){	
		$nianji = FLEA::getSingleton('Table_Lankinglists');
		if($this->_isPost()){			
			$data	=	$_POST;
			if($nianji->save($data)){
				$this->_alert("成功修改！","index.php?controller=Admin&action=LankinglistList");exit;
			}else{
				$this->_alert("修改发生错误，请重来！","index.php?controller=Admin&action=LankinglistList");exit;
			}
		}else{		
			if(!isset($_GET["id"])){$this->_alert("参数错误！",$_SERVER['HTTP_REFERER']);exit;}
			$nianji = FLEA::getSingleton('Table_Lankinglists');
			$row	=	$nianji->find((int)$_GET["id"]);
			dump($row);
			include(APP_DIR."/adminView/LankinglistEdit.html");		
		}	
	}
	function actionLankinglistDel(){
		$nianji = FLEA::getSingleton('Table_Lankinglists');
		if(!isset($_GET["id"])){$this->_alert("参数错误，请重来！","index.php?controller=Admin&action=LankinglistList");exit;}
		if($nianji->removeByPkv((int)$_GET['id'])){
			$this->_alert("成功删除记录！","index.php?controller=Admin&action=LankinglistList");exit;
		}else{
			$this->_alert("删除记录发生错误，请重试！",$_SERVER['HTTP_REFERER']);exit;
		}	
	}
	
	
	//查询moda的申请用户
	function actionModaCk(){
		
		$ck = FLEA::getSingleton('Table_ModaUser');
		$pagesize	=	20;
		$conditions	=	"pass = 0";
		$sortby		=	"user_id DESC";
		FLEA::loadClass('Lib_Pager');
		$page	=	new Lib_Pager( $ck, $pagesize, $conditions , $sortby );
		$rowset	=	$page->rowset;	
		
		//$result = $ck->findAll(" pass = 0","user_id DESC ");
		include(APP_DIR."/adminView/modack.html");		
	}
	
	
	
	//审核通过
		function actionAgree(){
			
			$ck = FLEA::getSingleton('Table_ModaUser');
			
			$result = $ck->find((int)$_GET["id"]);
			$result['pass']=1;
			$ck->update($result);
			
			
			/*处理展示
			*/
			$data['user_id'] = (int)$_GET["id"];
			$data['available'] = 1;
			$tableModaShows = FLEA::getSingleton('Table_ModaShows');
			$tableModaShows->failByUserId($data);
			
			
			$this->_alert("通过审核","index.php?controller=Admin&action=ModaCk");
		}
		
		
		//待审核操作
		function actionNoAgree(){
			$ck = FLEA::getSingleton('Table_ModaUser');
					
			$result = $ck->find((int)$_GET["id"]);
			$result['pass'] = 2;
			$ck->update($result);
			
			/*处理展示
			*/
			$data['user_id'] = (int)$_GET["id"];
			$data['available'] = 0;
			$tableModaShows = FLEA::getSingleton('Table_ModaShows');
			$tableModaShows->failByUserId($data);
			
			/*美女榜展示处理
			*/
			
			$user_id = (int)$_GET['id'];
			
			$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
			$tableModaRankShow->delRankShowByUserId($user_id);
			
			
			//$ck->removeByPkv((int)$_GET["id"]);
			$this->_alert("待审核中","index.php?controller=Admin&action=ModaCk");
		}
		//查看审核详图
		function actionXiangTu(){
			$ck = FLEA::getSingleton('Table_ModaUser');
			$result = $ck->find((int)$_GET["id"]);
			include(APP_DIR."/adminView/xiangtu.html");
		}
		
		//访谈
		function actionModaNews(){		
			include(APP_DIR."/adminView/modanews.html");
		}
		//添加访谈
		function actionAddNews(){
			$table = FLEA::getSingleton('Table_ModaNews');
			if($_POST['content']==""){
				$this->_alert('请填写详细内容');
				exit;
			}
			$arr=array(
						'news_title'   =>$_POST['news_title'],
						'news_content' =>$_POST['news_content'],
						'news_st' =>$_POST['news_st'],
						'content' =>$_POST['content']
						);
			$table->save($arr);	
		
			$this->_alert("添加成功");
		}
		
		//查询所有访谈
		function actionAllNews(){
			$table = FLEA::getSingleton('Table_ModaNews');
			$pagesize	=	20;
			$conditions	=	"";
			$sortby		=	"news_id desc";
			FLEA::loadClass('Lib_Pager');
			$page	=	new Lib_Pager( $table, $pagesize, $conditions , $sortby );
			$rowset	=	$page->rowset;	
			
			
			
			//$news = $table->findAll("","news_id desc");
			include(APP_DIR."/adminView/allnews.html");
		
		}	
		//修改访谈信息
		function actionEditNews(){
			$table = FLEA::getSingleton('Table_ModaNews');
			$news = $table->find("news_id='".(int)$_GET['id']."'");
			include(APP_DIR."/adminView/editnews.html");
		}
		
		
		
		function actionEditModaNews(){
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
			
			
			
			$table->update($news);
			$result = move_uploaded_file($_FILES['img_url']['tmp_name'],$newname);
			$result1 = move_uploaded_file($_FILES['url']['tmp_name'],$newname1);
			
			$this->_alert("修改成功","?controller=Admin&action=AllNews");
		
		}
		
		
	//搜索美达会员 
		//function actionDelModaUser(){
//			include(APP_DIR."/adminView/delmodauser.html");
//		}
		
	//根据昵称删除
		//function actionDelUser(){
//			$ck = FLEA::getSingleton('Table_ModaUser');
//			$result = $ck->find(" nickname = '".$_POST['nickname']."'");
//			if($result['pass']==1){
//				$ck->remove($_POST['nickname']);
//				$this->_alert("删除成功","index.php?controller=Admin&action=DelModaUser");
//			}else{
//				$this->_alert("该昵称不是会员","index.php?controller=Admin&action=DelModaUser");
//			}
//			
//			
//		}
		//查询已注册会员
			function actionSearchAllUser(){
				$ck = FLEA::getSingleton('Table_ModaUser');
				$pagesize	=	20;
				$conditions	=	"pass = 1";
				$sortby		=	"user_id DESC";
				FLEA::loadClass('Lib_Pager');
				$page	=	new Lib_Pager( $ck, $pagesize, $conditions , $sortby );
				$rowset	=	$page->rowset;
		
				//$result = $ck->findAll(" pass = 1 ");
				include(APP_DIR."/adminView/searchalluser.html");
				
			}
		//查询注册会员的待审核	
/*		function actionSNoAgree(){
			$ck = FLEA::getSingleton('Table_ModaUser');		
			$result = $ck->find((int)$_GET["id"]);
			$result['pass'] = 2;
			$ck->update($result);
			//$ck->removeByPkv((int)$_GET["id"]);
			$this->_alert("待审核中","index.php?controller=Admin&action=SearchAllUser");
		}	
			*/
		//删除已注册会员	
			function actionDeleteModaUser(){
				$ck = FLEA::getSingleton('Table_ModaUser');
				//$this->_alert((int)$_GET["id"]);
				$ck->removeByPkv((int)$_GET["id"]);
				$this->_alert("已删除","index.php?controller=Admin&action=SearchAllUser");
				
			}
			//已注册会员详图
			function actionUserXiangTu(){
			$ck = FLEA::getSingleton('Table_ModaUser');
			$result = $ck->find((int)$_GET["id"]);
			include(APP_DIR."/adminView/userxiangtu.html");
		}
			
			
			
		//根据昵称搜索注册会员	
			function actionSearchUser(){
				$ck = FLEA::getSingleton('Table_ModaUser');
				//$result = $ck->find("nickname='".$_POST['nickname']."'");
				if($_POST['nickname']!='')
					$res = $ck->findAll("nickname like '%".$_POST['nickname']."%'");
				
				include(APP_DIR."/adminView/searchmodauser.html");
			}
			
			
			
			//根据昵称搜索注册会员详图	
			function actionUXiangTu(){
				$ck = FLEA::getSingleton('Table_ModaUser');
				$result = $ck->find((int)$_GET["id"]);
				include(APP_DIR."/adminView/uxiangtu.html");
			}
			
			//删除搜索注册会员
				function actionDelUser(){
					$ck = FLEA::getSingleton('Table_ModaUser');
					//$this->_alert((int)$_GET["id"]);
					$ck->removeByPkv((int)$_GET["id"]);
					$this->_alert("已删除","index.php?controller=Admin&action=SearchUser");
					
				}
				
		//根据昵称搜索待审核会员		
			function actionSearchWaitUser(){
				if($this->_isPost()){
					if($_POST['nickname']==""){
						$this->_alert("昵称不能为空");
						exit;
					}
					$ck = FLEA::getSingleton('Table_ModaUser');
					$result = $ck->find("nickname='".$_POST['nickname']."' And pass != 1");
					if(!$result){
						$this->_alert('该昵称不是待审核会员和审核会员');
					
					}
					include(APP_DIR."/adminView/searchwaituser.html");
				}else{
					include(APP_DIR."/adminView/searchwaituser.html");
				}
			}
			//删除根据昵称搜索的待审核会员
			function actionDelSearchWaitUser(){
					$ck = FLEA::getSingleton('Table_ModaUser');
					//$this->_alert((int)$_GET["id"]);
					$ck->removeByPkv((int)$_GET["id"]);
					$this->_alert("已删除","index.php?controller=Admin&action=SearchWaitUser");
					
				}
				
		//根据昵称搜索的待审核会员详图
			function actionSWaitXiangTu(){
				$ck = FLEA::getSingleton('Table_ModaUser');
				$result = $ck->find((int)$_GET["id"]);
				include(APP_DIR."/adminView/swaitxiangtu.html");
			}
			
		//待审核会员
			function actionWaitModaCk(){
				$ck = FLEA::getSingleton('Table_ModaUser');
				$pagesize	=	20;
				$conditions	=	"pass = 2";
				$sortby		=	"user_id DESC";
				FLEA::loadClass('Lib_Pager');
				$page	=	new Lib_Pager( $ck, $pagesize, $conditions , $sortby );
				$rowset	=	$page->rowset;
				//$result = $ck->findAll("pass = 2");
				include(APP_DIR."/adminView/waituser.html");
			
			}
			
			
		//待审核通过
		function actionWaitAgree(){
			
			$ck = FLEA::getSingleton('Table_ModaUser');
			
			$result = $ck->find((int)$_GET["id"]);
			$result['pass']=1;
			$ck->update($result);
			$this->_alert("通过审核","index.php?controller=Admin&action=WaitModaCk");
		}	
		//查看待审核详图
			function actionWaitXiangTu(){
			$ck = FLEA::getSingleton('Table_ModaUser');
			$result = $ck->find((int)$_GET["id"]);
			include(APP_DIR."/adminView/waitxiangtu.html");
		}
			
		////查询待审核会员
//			function actionWaitSearchUser(){
//				$ck = FLEA::getSingleton('Table_ModaUser');
//				$result = $ck->find(" nickname = '".$_POST['nickname']."'");
//				if($result['pass']==2){
//					include(APP_DIR."/adminView/waituser.html");
//				}else{
//					$this->_alert("该昵称不是待审核会员","index.php?controller=Admin&action=DelModaUser");
//				}
//			
//			}
//			//删除待审核会员
				function actionDelWaitUser(){
					$this->_alert("为了保证数据丰富性，禁止删除","index.php?controller=Admin&action=WaitModaCk");
					exit;
					/*删除相关展示及展示评论
					*/
					$tableModaShows = FLEA::getSingleton('Table_ModaShows');
					$tableModaShows->deleteAllByUserId((int)$_GET["id"]);
					
					/*删除贴吧及相关内容
					*/
					$tableModaClub = FLEA::getSingleton('Table_ModaClub');
					$tableModaClub->deleteTotalByUid((int)$_GET["id"]);
					
					/*删除用户
					*/
					$ck = FLEA::getSingleton('Table_ModaUser');	
					$ck->removeByPkv((int)$_GET["id"]);
					
					
					$this->_alert("已删除","index.php?controller=Admin&action=WaitModaCk");
					//include(APP_DIR."/adminView/waituser.html");
				}
				
				
				
				
			//排行榜头衔
				function actionTouXian(){
					
					include(APP_DIR."/adminView/touxian.html");
				}
				
				function actionAddTX(){
					$tx = FLEA::getSingleton('Table_ModaTouXian');	
					$rank = FLEA::getSingleton('Table_ModaRanks');
					if($_POST['rank_title']==""||$_POST['txname']==""){
					
						$this->_alert('填写内容不能为空');
						exit;
					}
					
					$result = $rank->find("rank_title = '".$_POST['rank_title']."'");
					$arr =array(
								'rank_title'=>	$_POST['rank_title'],
								'rank_id'   =>  $result['rank_id'],
								'txname'    =>  $_POST['txname']								
							); 
							
					$tx->save($arr);		
					$this->_alert("添加成功");
				}	
			//给选手添加头衔
					function actionAddUserTX(){
						if($this->_isPost()){
							$tablemes = FLEA::getSingleton('Table_ModaRankMes');
							$tx = FLEA::getSingleton('Table_ModaTouXian');
							//查找排行榜对应头衔
							$result = $tx->find("rank_id = '".(int)$_POST['rank_id']."' And txname = '".$_POST['txname']."'");
							//查找排行榜对应选手
							$result1 = $tablemes->find("rank_id='".(int)$_POST['rank_id']."' And nickname = '".$_POST['nickname']."'");
							
							$result1['touxian'] = $result['touxian'];
							
							$tablemes->update($result1);
							
							$tablerank = FLEA::getSingleton('Table_ModaRanks');
							$rank = $tablerank->find("rank_id='".$_POST['rank_id']."'");
							$_SESSION['rank_title']=$rank['rank_title'];
							$this->_alert('添加成功',"?controller=Admin&action=RankList");
							 
						}else{
								$rank_id = (int)$_GET['ids'];
								$nickname = $_GET['id'];
							include(APP_DIR."/adminView/addusertouxian.html");
						}
					
					}
					
					
					
					
				//查询排行榜头衔
					function actionSearchTouXian(){
						if($this->_isPost()){
							$tx = FLEA::getSingleton('Table_ModaTouXian');					
							$result = $tx->findAll("rank_title='".$_POST['rank_title']."'");
							include(APP_DIR."/adminView/ranktx.html");
						
						}else{
							include(APP_DIR."/adminView/searchtx.html");
						
						}
					
					}
				//修改头衔
				function actionEditTX(){
					$tx = FLEA::getSingleton('Table_ModaTouXian');
					if($this->_isPost()){				
						$result1 = $tx->find("id = '".(int)$_POST['id']."'");
						$result1['txname'] = $_POST['txname'];
						
						$uploaddir ='modanewspic/';	
						$file_name = $_FILES['touxian']['name'];
						$file_postfix = substr($file_name,strrpos($file_name,"."));
						$newname	=	$uploaddir."/".rand(1000,9999).time().$file_postfix;
						
						if($file_name){
							if($result1['touxian']){
								unlink($result1['touxian']);
							}
							$result1['touxian'] =$newname;
						}
						
						$tx->update($result1);
						$r = move_uploaded_file($_FILES['touxian']['tmp_name'],$newname);
						
						
					
					}else{
						$result = $tx->find("id = '".(int)$_GET['id']."'");
						
						include(APP_DIR."/adminView/edittx.html");
					}
				}		
				
				
				
				
				
				
			//排行榜
			//添加修改排行榜
			function actionAddRank(){
				//dump($_GET['id']);
				
			
					if($this->_isPost()){	
						$table = FLEA::getSingleton('Table_ModaRanks');
						$arr = array(
									'rank_title' => $_POST["rank_title"],
									'content'    => $_POST["content"],
									//'overtime'	=>  strtotime($_POST["overtime"])	
									'overtime'	=>  $_POST["overtime"]
								);
						//$table->save($arr);		
						//$this->_alert("添加成功");
						
						$uploaddir = 'modanewspic/';
						$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
						$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
						
						$file_name =  $_FILES['firstimg']['name'];
						$file_postfix = substr($file_name,strrpos($file_name,"."));
						$size=$_FILES[$upload_name]['size'];
						
						$uploadfile = $uploaddir ."frist".basename($_FILES['firstimg']['name']);
						//dump($uploadfile);
						
						if(in_array($file_postfix,$limittyppe))
						{
							if (move_uploaded_file($_FILES['firstimg']['tmp_name'], $uploadfile)) 
							{
								$arr['first_mark'] = $uploadfile;
	
								
							}
							else
							{
								$this->_alert("第一名图标上传失败");
							}
							
						}
						else
						{
							$this->_alert("第一名图标上传格式不对，要上传图片");
						}
						
						
					
						$file_name =  $_FILES['secimg']['name'];
						$file_postfix = substr($file_name,strrpos($file_name,"."));
						$size=$_FILES[$upload_name]['size'];
						$uploadfile = $uploaddir ."sec".basename($_FILES['secimg']['name']);
						//dump($uploadfile);
						
						
						if(in_array($file_postfix,$limittyppe))
						{
							if (move_uploaded_file($_FILES['secimg']['tmp_name'], $uploadfile)) 
							{
	
								$arr['sec_mark'] = $uploadfile;
								
							}
							else
							{
								$this->_alert("第二名图标上传失败");
							}
							
						}else
						{
							$this->_alert("第二名图标上传格式不对，要上传图片");
						}
						
						
						
						$file_name =  $_FILES['thrimg']['name'];
						$file_postfix = substr($file_name,strrpos($file_name,"."));
						$size=$_FILES[$upload_name]['size'];
						$uploadfile = $uploaddir ."thr".basename($_FILES['thrimg']['name']);
						//dump($uploadfile);
						
						
						if(in_array($file_postfix,$limittyppe))
						{
							if (move_uploaded_file($_FILES['thrimg']['tmp_name'], $uploadfile)) 
							{
								$arr['thr_mark'] = $uploadfile;
								//unlink($rest['temp_img']);
								//$tableModaRanks->updatetar($data);
								
							}
							else
							{
								$this->_alert("第三名图标上传失败");
							}
							
						}else
						{
							$this->_alert("第三名图标上传格式不对，要上传图片");
						}
						
						
							
						$table->save($arr);			
						$this->_alert("添加成功");	
								
					}
				
				include(APP_DIR."/adminView/addrank.html");
			}
			
			
			
			/*排行榜修改
			*/
			function actionRankEidt()
			{
			
				$rank_id = (int)$_GET['rank_id'];
				//dump($rank_id);
				
					if($this->_isPost())
					{	
					
						$rank_id = (int)$_POST['rank_id'];
					
						$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
						$ranD = $tableModaRanks->getRankById($rank_id); 
						
						
						
						$rankDat['rank_id'] = $rank_id;
						$rankDat['rank_title'] = $_POST["rank_title"];
						$rankDat['content'] = $_POST["content"];
						//$rankDat['overtime'] = strtotime($_POST["overtime"])	;
						$rankDat['overtime'] = $_POST["overtime"]	;
						//dump($_POST["overtime"]);
						//dump($rankDat['overtime']);
						
						
/*						$upload_name = "head_img";
								
						$file_name =  $_FILES[$upload_name]['name'];
						if($file_name)
						{
							
							unlink($ranD['img']);
							
							
			
							$file_postfix = substr($file_name,strrpos($file_name,"."));
							$newname	=	"/modanewspic/head_img".time().$file_postfix;
						
							$size=$_FILES[$upload_name]['size'];
					
							$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
								
							if(!in_array($file_postfix,$limittyppe) || $size>2048000){
								$this->_alert("图片2M内");
								exit;
								return false ;
							}else{
									 $temp_name = $_FILES[$upload_name]['tmp_name'];
									 
									 $result = move_uploaded_file($temp_name,".".$newname);
									 if($result)
									 {
										$rankDat['img'] = ".".$newname;
									 }else 
									 {
									 	$this->_alert("未知错误");
										return false;
									 }
							 }
						
						
						}
						
						
						$upload_name = "firstimg";
						$file_name =  $_FILES[$upload_name]['name'];
						//dump($file_name);
						if($file_name)
						{
							
							unlink($ranD['first_mark']);
							
							
			
							$file_postfix = substr($file_name,strrpos($file_name,"."));
							$newname	=	"/modanewspic/first".time().$file_postfix;
						
							$size=$_FILES[$upload_name]['size'];
					
							$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
								
							if(!in_array($file_postfix,$limittyppe) || $size>2048000){
								$this->_alert("图片2M内");
								exit;
								return false ;
							}else{
									 $temp_name = $_FILES[$upload_name]['tmp_name'];
									 
									 $result = move_uploaded_file($temp_name,".".$newname);
									 if($result)
									 {
										$rankDat['first_mark'] = ".".$newname;
									 }else 
									 {
									 	$this->_alert("未知错误");
										return false;
									 }
							 }
						
						
						}
						
						$upload_name = "secimg";
						$file_name =  $_FILES[$upload_name]['name'];
						//dump($file_name);
						if($file_name)
						{
							
							unlink($ranD['sec_mark']);
							
							
			
							$file_postfix = substr($file_name,strrpos($file_name,"."));
							$newname	=	"/modanewspic/sec".time().$file_postfix;
						
							$size=$_FILES[$upload_name]['size'];
					
							$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
								
							if(!in_array($file_postfix,$limittyppe) || $size>2048000){
								$this->_alert("图片2M内");
								exit;
								return false ;
							}else{
									 $temp_name = $_FILES[$upload_name]['tmp_name'];
									 
									 $result = move_uploaded_file($temp_name,".".$newname);
									 if($result)
									 {
										$rankDat['sec_mark'] = ".".$newname;
									 }else 
									 {
									 	$this->_alert("未知错误");
										return false;
									 }
							 }
						
						
						}
						
						$upload_name = "thrimg";
						$file_name =  $_FILES[$upload_name]['name'];
						//dump($file_name);
						if($file_name)
						{
							
							unlink($ranD['thr_mark']);
							
							
			
							$file_postfix = substr($file_name,strrpos($file_name,"."));
							$newname	=	"/modanewspic/thr".time().$file_postfix;
						
							$size=$_FILES[$upload_name]['size'];
					
							$limittyppe	=	array('.jpg','.JPG','.gif','.png','.PNG','.BMP','.bmp','.GIF');
								
							if(!in_array($file_postfix,$limittyppe) || $size>2048000){
								$this->_alert("图片2M内");
								exit;
								return false ;
							}else{
									 $temp_name = $_FILES[$upload_name]['tmp_name'];
									 
									 $result = move_uploaded_file($temp_name,".".$newname);
									 if($result)
									 {
										$rankDat['thr_mark'] = ".".$newname;
									 }else 
									 {
									 	$this->_alert("未知错误");
										return false;
									 }
							 }
						
						
						}
*/						
						
						
						
						
						
						
										
						$rrr = $tableModaRanks->save($rankDat);		
						if($rrr)
							$this->_alert("修改成功","index.php?controller=Admin&action=AllRank");
						else
							$this->_alert("错误，请检查上传图片格式大小等","index.php?controller=Admin&action=AllRank");
						exit;		
					}
				
				//dump($rank_id);
				$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
				$rankDat = $tableModaRanks->getRankById($rank_id);
				//dump($rankDat);
				
				
				include(APP_DIR."/adminView/RankEidt.html");	
			
			}
			
			
			
			
			//查询所有排行榜
				function actionAllRank(){
					$tableranks = FLEA::getSingleton('Table_ModaRanks');
					$pagesize	=	20;
					$conditions	=	"";
					$sortby		=	"rank_id DESC";
					FLEA::loadClass('Lib_Pager');
					$page	=	new Lib_Pager( $tableranks, $pagesize, $conditions , $sortby );
					$rowset	=	$page->rowset;
					
					include(APP_DIR."/adminView/allrank.html");
				}
			
			
			
			// 查询排行榜所有成员信息
			
			function actionSearchRank(){
				
				include(APP_DIR."/adminView/searchrank.html");
			}
			
			
			
				function actionRankList(){
	
					$tableuser = FLEA::getSingleton('Table_ModaUser');
						
					$tableranks = FLEA::getSingleton('Table_ModaRanks');
					if(!$_SESSION['rank_title']){	
					$ranks = $tableranks->find("rank_title = '".$_POST["rank_title"]."'");
					}
					else{
					$ranks = $tableranks->find("rank_title = '".$_SESSION['rank_title']."'");
					}
					unset($_SESSION['rank_title']);

//					//$this->_alert($ranks);
					$tablemes = FLEA::getSingleton('Table_ModaRankMes');
					
					$mes = $tablemes->findAll("rank_id ='".$ranks['rank_id']."'","ticket desc");

					
					include(APP_DIR."/adminView/ranklist.html");

				}	
				
				
				
				
				
				
				//添加美达成员
				function actionAddUser(){
					//session_start();	
//							$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
//							$head_img =$_SESSION['random_key'];
//							$path='head_img/'.$head_img.basename($_FILES['file']['name']);
				
					
					//添加
					if($this->_isPost()){
						//查询username
						$port = FLEA::getSingleton('Table_ModaUser');
						$portresult = $port->find("nickname='".$_POST['nickname']."' And pass = 1");
						//查询rank_id
						$tableranks = FLEA::getSingleton('Table_ModaRanks');
						$ranks = $tableranks->find("rank_title='".$_POST['rank_title']."'");
						
						$tablemes = FLEA::getSingleton('Table_ModaRankMes');
						$mes = $tablemes->find("nickname='".$_POST['nickname']."' And rank_id ='".$ranks['rank_id']."'");
				
						if(!$portresult){
							$this->_alert("不是美达会员","index.php?controller=Admin&action=AddUser");
							exit;
						}
						if($mes){
							$this->_alert("已添加过此成员","index.php?controller=Admin&action=AddUser");
							exit;
						}
						if(!$ranks['rank_title']){
							$this->_alert("没有此排行榜","index.php?controller=Admin&action=AddUser");
							exit;
						}
						//if(move_uploaded_file($_FILES['file']['tmp_name'],$path)){
//								
//						}
							
							
							$arr=array(
									'username'=>$portresult['username'],
									'nickname'=>$_POST['nickname'],
									'rank_id'=>	$ranks['rank_id'],	
									'ticket'=>$_POST['ticket'],
									'user_id'=>$portresult['user_id']
									//'head_img'=>$path
									//'head_img'=>$_POST['picurl']
								);
							
							$tablemes->save($arr);
							unset($_SESSION['p']);
							$this->_alert("添加成功","index.php?controller=Admin&action=AddUser");
							
					}
					
					
				
					include(APP_DIR."/adminView/adduser.html");
				}
				
				
				//添加美女榜展示
				function actionAddTopGirlShow(){
		
					
					$show_id = (int)$_GET['show_id'];
					
					$visitUser_id = (int)$_GET['user_id'];
					
					$rank_title = $_GET['rank_title'];
					
					$title = $_GET['title'];
					
					//dump($rank_title);
					
						//查询rank_id
						$tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
											
						$rankDa = $tableModaRanks->getRankByRankTile($rank_title);
						
						//dump($rankDa);
						
						if(!$rankDa)
						{
							$this->_alert("榜不存在");
							exit;
						}
						
						
						
						/*读取Table_ModaShows
						*/		
						$tableModaShows = FLEA::getSingleton('Table_ModaShows');
						$showData = $tableModaShows->getShowByShowId($show_id);
						//dump($showData);
						if(!$showData)
						{
							$this->_alert("展示不存在");
							exit;
						}
						
						$RankGirlDat['head_img'] = $showData['show_img'];
						$RankGirlDat['rank_id'] = $rankDa['rank_id'];
						$RankGirlDat['rank_title'] = $rank_title;
						$RankGirlDat['user_id'] = $visitUser_id;
						$RankGirlDat['show_id'] = $show_id;
						$RankGirlDat['title'] = $title;
						
						
						
						//dump($RankGirlDat);
						/*添加展示
						*/
						$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
						$rr = $tableModaRankShow->savetar($RankGirlDat);
						if($rr)
							$this->_alert("添加成功","index.php?controller=Admin");
						else
						{
							$this->_alert("未知错误","index.php?controller=Admin");
							exit;
						}
					//include(APP_DIR."/adminView/AddTopGirlShow.html");
				}	
				
				
							
				
				
				
				//修改选手信息
					 function actionEditUser(){
						//session_start();	
//							$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
//							$head_img =$_SESSION['random_key'];
//							$path='head_img/'.$head_img.basename($_FILES['file']['name']);
							//$path2=basename($_FILES['file']['name']);
							////$path2 = $_POST['picurl'];
						//$_SESSION['id'] = $_GET['id'];
//						$_SESSION['ids']=$_GET['ids'];
						
						$tablemes = FLEA::getSingleton('Table_ModaRankMes');
						$mes = $tablemes->find("nickname='".$_GET['id']."' And rank_id = '".$_GET['ids']."'");
						
						$tableranks = FLEA::getSingleton('Table_ModaRanks');
						$ranks=$tableranks->find("rank_id='".$mes['rank_id']."'");
						
						
						if($this->_isPost()){
							$mes['ticket']=	$_POST['ticket'];
							//if($path2!=""){
//								if($mes['head_img']){
//									unlink($mes['head_img']);
//								}
//								$mes['head_img']=$path2;
								
								//if(move_uploaded_file($_FILES['file']['tmp_name'],$path)){
//								
//								}
								
							//
//							}	
							$_SESSION['rank_title'] = $_POST['rank_title'];
							
							
							$tablemes->update($mes);
							unset($_SESSION['p']);
							$this->_alert("修改成功","index.php?controller=Admin&action=RankList");
							
						}
							
							
						
						
						include(APP_DIR."/adminView/edituser.html");
					
					}
					
					
					
					
					//删除选手
					function actionDeleteUsere(){
					
						$tablemes = FLEA::getSingleton('Table_ModaRankMes');
						$mes = $tablemes->find("nickname='".$_GET['id']."' And rank_id='".$_GET['ids']."'");
						if($mes['head_img']){
							unlink($mes['head_img']);
						
						}
						$tablemes->remove($mes);
						$this->_alert("已删除","?controller=Admin&action=SearchRank");
					
					}
					
					
					
					
					
					
					//头像
	/*function actionUploadPic(){
		if($_POST['upload']=='upload'){
			
			session_start(); 
			$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
			$big_img=$_SESSION['random_key'];
		
			$uploaddir ='uploadd/';
			$uploadfile = $uploaddir .$big_img .basename($_FILES['file']['name']);
			$_SESSION['big']=$uploadfile;
			//$_SESSION['uploadfile']= basename($_FILES['file']['name']);
			
			$_SESSION["fullname"]= basename($_FILES['file']['name']);
			
			//print_r($_FILES['file']);
			//echo $uploadfile;
			//$_SESSION["filename"]	=	$uploadfile;
			//	
			$_FILES['file']['name']=strtolower($_FILES['file']['name']);
			$tmp_filename=explode(".",$uploadfile);
			if($tmp_filename[(count($tmp_filename)-1)]=='jpg' || $tmp_filename[(count($tmp_filename)-1)]=='jpeg'){
				$up_filename=$updir.md5($_FILES['file']['name'].$_FILES['file']['size']).".".$tmp_filename[(count($tmp_filename)-1)] ;	
				
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
				list($w, $h, $type, $attr)=getimagesize($uploadfile);
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
				
				$jsstr	=	 '<script language="javascript">parent.$("#showBig").html("'.$f1.'");parent.$("#showThumb").html("'.$f2.'");parent.goss();parent.$("#bigwidth").val("'.$w.'");parent.$("#bigheight").val("'.$h.			'");parent.$("#bigImage").val("'.$uploadfile.'");</script>';
					
			}else {
				$jsstr	= "<script>alert('文件上传失败!');</script>";
			}
			
			//
				}else{
					echo "<script>alert('图片上传失败,请注意上传格式，目前只支持jpg格式图片!');location.href='?controller=Admin&action=UploadPic'</script>";exit;
				}
			
			include(APP_DIR . "/adminView/uploadPhoto.html");
		}else{
			include(APP_DIR . "/adminView/uploadPhoto.html");	
		}
			
		
	}
	
	function actionSaveThumb(){
		session_start(); 
		
			$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
			
			
		
		$thumb_image_name =$_SESSION['random_key'];
		$paths='thumb_image/'.$thumb_image_name.$_SESSION["fullname"];
		
		//$this->_alert("dfdf");
		
		if($this->_isPost()){
			$targ_w = $targ_h = 150;
			$jpeg_quality = 100;
			$src = $_POST['bigImage'];
			$img_r = imagecreatefromjpeg($src);
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );	
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);		
			header('Content-type: image/jpeg');
			imagejpeg($dst_r,$paths,$jpeg_quality);
			$_SESSION['p']=$paths;
			
			//$table=FLEA::getSingleton('Table_Users');
//			//111111111111111111111111111111111111111111
//			$result=$table->find("username='1111'");
//			if($result['head_img']){
//			unlink($result['head_img']);
//			
//			}
//			$result['head_img']=$path;			
//			$table->update($result);
			
			
			
			
				
			//unlink($_SESSION["filename"]);
//			unset($_SESSION["filename"]);
			unset($_SESSION["fullname"]);
			unlink($_SESSION['big']);
			//redirect(url('Admin','AddUser'));
			
			//redirect(url('Cover','SaveHead'));
			include(APP_DIR . "/adminView/adduser.html");	
			exit;
		}else{
		include(APP_DIR . "/adminView/saveThumb.html");
	  }
	
	}
	
	
	
	//头像修改
	function actionUploadPice(){
		if($_POST['upload']=='upload'){
			
			session_start(); 
			$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
			$big_img=$_SESSION['random_key'];
		
			$uploaddir ='uploadd/';
			$uploadfile = $uploaddir .$big_img .basename($_FILES['file']['name']);
			$_SESSION['big']=$uploadfile;
			//$_SESSION['uploadfile']= basename($_FILES['file']['name']);
			
			$_SESSION["fullname"]= basename($_FILES['file']['name']);
			
			//print_r($_FILES['file']);
			//echo $uploadfile;
			//$_SESSION["filename"]	=	$uploadfile;
			//	
			$_FILES['file']['name']=strtolower($_FILES['file']['name']);
			$tmp_filename=explode(".",$uploadfile);
			if($tmp_filename[(count($tmp_filename)-1)]=='jpg' || $tmp_filename[(count($tmp_filename)-1)]=='jpeg'){
				$up_filename=$updir.md5($_FILES['file']['name'].$_FILES['file']['size']).".".$tmp_filename[(count($tmp_filename)-1)] ;	
				
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
				list($w, $h, $type, $attr)=getimagesize($uploadfile);
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
				
				$jsstr	=	 '<script language="javascript">parent.$("#showBig").html("'.$f1.'");parent.$("#showThumb").html("'.$f2.'");parent.goss();parent.$("#bigwidth").val("'.$w.'");parent.$("#bigheight").val("'.$h.			'");parent.$("#bigImage").val("'.$uploadfile.'");</script>';
					
			}else {
				$jsstr	= "<script>alert('文件上传失败!');</script>";
			}
			
			//
				}else{
					echo "<script>alert('图片上传失败,请注意上传格式，目前只支持jpg格式图片!');location.href='?controller=Admin&action=UploadPice'</script>";exit;
				}
			
			include(APP_DIR . "/adminView/uploadPhoto.html");
		}else{
			include(APP_DIR . "/adminView/uploadPhoto.html");	
		}
			
		
	}
	
	function actionSaveThumbe(){
		session_start(); 
		
			$_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s'));
			
			
		
		$thumb_image_name =$_SESSION['random_key'];
		$paths='thumb_image/'.$thumb_image_name.$_SESSION["fullname"];
		
		//$this->_alert("dfdf");
		
		if($this->_isPost()){
			$targ_w = $targ_h = 150;
			$jpeg_quality = 100;
			$src = $_POST['bigImage'];
			$img_r = imagecreatefromjpeg($src);
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );	
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);		
			header('Content-type: image/jpeg');
			imagejpeg($dst_r,$paths,$jpeg_quality);
			$_SESSION['p']=$paths;
			//
			//$_SESSION['id'] ;
//			$_SESSION['ids'];
			
			//$table=FLEA::getSingleton('Table_Users');
//			//111111111111111111111111111111111111111111
//			$result=$table->find("username='1111'");
//			if($result['head_img']){
//			unlink($result['head_img']);
//			
//			}
//			$result['head_img']=$path;			
//			$table->update($result);
			
			
			
			
				
			//unlink($_SESSION["filename"]);
//			unset($_SESSION["filename"]);
			unset($_SESSION["fullname"]);
			unlink($_SESSION['big']);
			//redirect(url('Admin','AddUser'));
			$this->_alert("图片获取成功success");
			//redirect(url('Cover','SaveHead'));
			include(APP_DIR . "/adminView/edituser.html");	
			exit;
		}else{
		include(APP_DIR . "/adminView/saveThumb.html");
	  }
	
	}*/
					
					
					
					
					
					
					
					
					
	
	//管理员登录界面
	function actionindex(){
		include(APP_DIR . '/adminView/adminIndex.html');		
	}	
	function actiontopframe(){
		include(APP_DIR . '/adminView/topFrame.html');
	}
	function actionnavigation(){
		include(APP_DIR . '/adminView/navigation.html');
	}
	function actionswitch(){
		include(APP_DIR . '/adminView/switch.html');
	}	
	function actionwelcome(){
		//$jsarr	=	$this->_returnmenu();
		include(APP_DIR . '/adminView/welcome.html');
	}
	
	
	function actionLogin(){
		
	
	}
	
	
	
function _changePage($url="",$target="self"){
		if($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script type=\"text/javascript\" >$target.location='$url';</script>
</head><body></body></html>";
	}	
	
	
	
}


/////通过是pass = 1,待审核pass = 2,新申请pass = 0
?>