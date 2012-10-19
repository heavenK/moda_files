<?php

FLEA::loadClass('Controller_BoBase');
class Controller_Index3 extends Controller_BoBase
{
	//加壳函数
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
						
						$str = htmlspecialchars($str);
					  
					  return $str;
				
	
	}


    /**
     * 显示所有
     */	 
    function actionIndex(){
	
		$jg	=	FLEA::getSingleton('Table_Polls');
		if($this->_isPost()){
			////截止代码
			//$this->_alert("投票截止！","http://moda.we54.com");exit();
			/*kaishi*/
			if((time()-strtotime("2009-6-22 0:0:0"))>0){
				$this->_alert("投票截止！","http://moda.we54.com");exit();
			}
			if($_SESSION["Zend_Auth"]['storage']->username==''){
				$this->_alert("您没有登录，请登录后进行投票！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));exit();
			}
			$usertable		=	FLEA::getSingleton('Table_Cdbmembers');
			$currentuser	=	$usertable->_getUserByUsername($_SESSION["Zend_Auth"]['storage']->username);
			if($currentuser['groupid']>3 and $currentuser['groupid']<=7){
				$this->_alert("积分达到初中生以上方可进行投票！","http://moda.we54.com");exit();
			}
			if($currentuser['groupid']>=9 and $currentuser['groupid']<=31 and $currentuser['groupid']!=22){
				$this->_alert("积分达到初中生以上方可进行投票！","http://moda.we54.com");exit();
			}
			var_dump($currentuser['groupid']);
			//echo $_SESSION["validate"].'--'.$_POST['validate'];
			//dump($_SESSION);
			$table = FLEA::getSingleton('Table_Pollips');	
			$tnum	=	count($_POST['poll_id']);
			if(!$table->checkip()){
				$this->_alert("一个IP一天只能投一次！");exit();
			}
			if($tnum!=1){
				$this->_alert("必须选择1个候选人，不能多也不能少！".$tnum);exit();
			}
			
			//验证码
			
			$ac = FLEA::getSingleton('Lib_AuthCode');
			if(!$ac->checkCode($_POST["validate"])){
				$this->_alert("验证码错误，请重试！");exit();
			}
			
			//echo "ss";
			//OK加票
			if($jg->vote($_POST['poll_id'])){
				$this->_alert("成功投票！");exit();
			}else{
				$this->_alert("发生未知错误，请重试！");exit();
			}
			/*kaishi*/		
		}else{		
			$jg->disableLinks();	
			$rowset	=	$jg->findAll('type=2','cixu DESC');	
			$rowset2	=	$jg->findAll('type=1','cixu DESC');	
			$rowset3	=	$jg->findAll('type=3','cixu DESC');	
			//dump($rowset);
			$usertable		=	FLEA::getSingleton('Table_Cdbmembers');
			$currentuser	=	$usertable->_getUserByUsername($_SESSION["Zend_Auth"]['storage']->username);
			include(APP_DIR . '/View/index3.html');		
		}
    }
	
	function actionContent(){
		if(isset($_GET["id"])){
			$id	=	(int)$_GET["id"];
			$table = FLEA::getSingleton('Table_Polls');	
			$row = $table->find("poll_id='".$id."'");
			if($row){
				$row['views']+=1;
				$table->save($row);
				include(APP_DIR . '/View/pic10-3.html');
			}else{
				$this->_alert("查无此人，非法链接");exit();
			}			
		}else{
			$this->_alert("非法链接");	exit();
		}			
	}
	
	
	
	
	
	/*注册
	*/
	
	function actionRegister()
	{
	
	
	
		if($_SESSION["Zend_Auth"]['storage']->username==''){
				$this->_alert("您没有登录，请登录后提交申请！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/?Controller=Index3&action=Register"));exit();
		}
		

		if($this->_isPost())
		{
				
				$modauser=FLEA::getSingleton('Table_ModaUser');	
				$modaresult=$modauser->find("username='".$_SESSION["Zend_Auth"]['storage']->username."'" );
				if($modaresult['pass'] == 1){
					//$this->_alert("您已经提交过美达信息，或者正待审核，请等待我们与您联系!~","http://moda.we54.com");exit();
					$this->_alert("此用户为美达用户，申请无效!~","http://moda.we54.com");exit();
				}
					
				//获取文件大小
				$size1 = $_FILES['img1']['size'];
				$size2 = $_FILES['img2']['size'];
				$size3 = $_FILES['img3']['size'];
				$size4 = $_FILES['img4']['size'];
				$size5 = $_FILES['img5']['size'];
				$size6 = $_FILES['img6']['size'];
				if($_FILES['img1']['tmp_name'] == '' ||$_FILES['img2']['tmp_name'] == '' ||$_FILES['img3']['tmp_name'] == '' ||$_FILES['img4']['tmp_name'] == '' ||$_FILES['img5']['tmp_name'] == '' ||$_FILES['img6']['tmp_name'] == '' ){
					$this->_alert("请上传6张照片");
					exit;
				}
				if($size1>1048576||$size2>1048576||$size3>1048576||$size4>1048576||$size5>1048576||$size6>1048576){
					$this->_alert("单个上传图片不能超过1MB！");
					exit;
				}
				if($_POST['truename']==''||$_POST['bdate']==''||$_POST['mobile']==''){
					$this->_alert("请确保资料填写完整");
					exit;
					
				}	

				
				$table=FLEA::getSingleton('Table_PassportUser');
				$result=$table->find("username='".$_SESSION["Zend_Auth"]['storage']->username."'" );
					
				$arr=array(
							  'username' => $result['username'],	
							  'nickname' => $result['nickname'],
							  'truename' => $this->_convertSpecialChars($_POST['truename']),
							  'errormes' => $this->_convertSpecialChars($_POST['instrper']),
							  'birthdate' => $_POST['bdate'],
							  'height' => (int)$_POST['height'],
							  'weight' => (int)$_POST['weight'],
							  'mobile' => (int)$_POST['mobile'],
							  'qq' => (int)$_POST['qq']	,
							  'restime' => time()	  
					);
				if($modaresult)
				{
					$arr['user_id']  = $modaresult['user_id'];
					$arr['restime']  = $modaresult['restime'];
				}
//				dump($modaresult);
//				dump($arr);
//				exit;
				
				if($modauser->save($arr))
				{
					$this->_alert('您的个人信息，已成功记录到美達大连美女榜资料库中,如符合标准,我们会与您联系.欢迎你继续关注美達大连美女榜,关注新青年！','http://moda.we54.com');				
				}
				else
				{
					$this->_alert('提交信息发生错误，请联系管理员！','http://moda.we54.com');
				}
	
		}
		else
		{
				//include(APP_DIR . '/View/sqjr333.html');
				//moda 版本切换
				if($_COOKIE['moda_version'] == 'version_2')
					include(APP_DIR."/".$_COOKIE['moda_version']."/sqjr333.html");
				elseif($_COOKIE['moda_version'] == 'version_1')
					include(APP_DIR."/View/sqjr333.html");
				else
					include(APP_DIR."/version_2/sqjr333.html");
		}		
		
	}
	
	
	
	
	
	//验证用户
	function actionRegCk(){
		if($_SESSION["Zend_Auth"]['storage']->username==''){
				$this->_alert("您没有登录，请登录后提交申请！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/?Controller=Index3&action=Register"));exit();
		}
	}
	
	function actionResult(){
			include 'php-ofc-library/open-flash-chart.php';
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		//header('Content-type: text/plain');//输出的类型

		header("Content-Type: text/html; charset=UTF-8");
		$jg	=	FLEA::getSingleton('Table_Polls');
		$rowset1	=	$jg->findAll();
		
		//$title = new title( date("D M d Y") );
		$title	=	new title( "大连新青年美达榜" );
		$title->set_style( '{color: #d070ac; font-size: 14px}' );
		
		
		
		$data=array();
		
		foreach($rowset1 as $val1){
			$bar = new bar_value($val1['votes']);
			$bar->set_tooltip( iconv( "gb2312", "UTF-8//IGNORE" , $val1['title'].'<br>票数：'.$val1['votes']) );
			//$bar->set_tooltip( mb_convert_encoding($val1['title'].'<br>票数：'.$val1['votes'], "UTF-8", "GBK") );
			$data[] = $bar;		
		}
		
		$data2=array();
		for( $i=0; $i<30; $i++){
			$data2[]	=	$i+5;		
		}

		
		$bar = new bar_sketch( '#d070ac', '#000000', 2 );
		$bar->set_values( $data );
		
		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $bar );
		$chart->set_bg_colour ('#ffffff');
		
		$x_axis = new x_axis();
		//$x_axis->labels = null;
		$x_axis->set_grid_colour ( '#ffffff');
		$x_axis->set_offset( 1 );
		$x_axis->set_labels_from_array( array('A','B','C','D','E','F','G','H','I'));
		
		$y_axis = new y_axis();
		$y_axis->set_range( 3000, 30000,5000 );
		$y_axis->set_grid_colour ( '#F2F2F2');
		$y_axis->labels = null;
		$y_axis->set_offset( false );
		
		
		$x_labels = new x_axis_labels();
		$x_labels->set_steps( 1 );
		$x_labels->set_vertical();
		//$x_labels->set_labels  ( '美女');
		// Add the X Axis Labels to the X Axis
		$x_axis->set_labels( $x_labels );
		
		$chart->add_y_axis( $y_axis );
		//$chart->add_x_axis( $x_axis );
		//$chart->y_axis = $y_axis;
		//$chart->x_axis = $x_axis;
		$chart->set_x_axis($x_axis); 
		
		
		
		include(APP_DIR . '/View/result10-3.html');
				
	}
		
	function actionAuthCode(){
		$ac = FLEA::getSingleton('Lib_AuthCode');
		$ac->genImg();
	}
	
	function actionData(){		

		include 'php-ofc-library/open-flash-chart.php';
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		//header('Content-type: text/plain');//输出的类型

		header("Content-Type: text/html; charset=utf-8");
		$jg	=	FLEA::getSingleton('Table_Polls');
		$rowset1	=	$jg->findAll();
		
		//$title = new title( date("D M d Y") );
		$title	=	new title( "大连新青年美达榜" );
		$title->set_style( '{color: #d070ac; font-size: 14px}' );
		
		
		
		$data=array();
		
		foreach($rowset1 as $val1){
			$bar = new bar_value($val1['votes']);
			//$bar->set_tooltip( $val1['title'].'<br>票数：'.$val1['votes']);
			$bar->set_tooltip("操你妈");
			//$bar->set_tooltip( iconv( "gb2312", "UTF-8//IGNORE" , $val1['title'].'<br>票数：'.$val1['votes']) );
			//$bar->set_tooltip( mb_convert_encoding($val1['title'].'<br>票数：'.$val1['votes'], "UTF-8", "GBK") );
			$data[] = $bar;		
		}
		
		$data2=array();
		for( $i=0; $i<30; $i++){
			$data2[]	=	$i+5;		
		}
		//$data2
	//	var_dump($data);
//		for( $i=0; $i<30; $i++)
//		{
//		   
//				// append a bar_value object to the data array
//				$bar = new bar_value($i);
//				$bar->set_tooltip( 'Hello<br>#val#' );
//				$data[] = $bar;
//			//}
//		}
//		var_dump($data);
		
		$bar = new bar_sketch( '#d070ac', '#000000', 2 );
		$bar->set_values( $data );
		
		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $bar );
		$chart->set_bg_colour ('#ffffff');
		
		$x_axis = new x_axis();
		//$x_axis->labels = null;
		$x_axis->set_grid_colour ( '#ffffff');
		$x_axis->set_offset( 1 );
		$x_axis->set_labels_from_array( array('A','B','C','D','E','F','G','H','I'));
		
		$y_axis = new y_axis();
		$y_axis->set_range( 3000, 30000,5000 );
		$y_axis->set_grid_colour ( '#F2F2F2');
		$y_axis->labels = null;
		$y_axis->set_offset( false );
		
		
		$x_labels = new x_axis_labels();
		$x_labels->set_steps( 1 );
		$x_labels->set_vertical();
		//$x_labels->set_labels  ( '美女');
		// Add the X Axis Labels to the X Axis
		$x_axis->set_labels( $x_labels );
		
		$chart->add_y_axis( $y_axis );
		//$chart->add_x_axis( $x_axis );
		//$chart->y_axis = $y_axis;
		//$chart->x_axis = $x_axis;
		$chart->set_x_axis($x_axis); 
		
		
		echo $chart->toPrettyString();


	}
	
	
	
		/*
	管理员用户
*/
function _CheckAdminRoot()
{
	if($_SESSION["Zend_Auth"]['storage']->adminid!=1){
				return false;
			}	
	else
		return true;		
	
	
}


		/*
检查用户权限
*/
	function _getUserAmin($visitUser_id = ''){
			$userName = $_SESSION["Zend_Auth"]['storage']->username;
			//$userName = "4444";
			
			if($userName==''){
				//$this->_alert("您没有登录！","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));exit();
				
				
				return false;
				
			}
			else
			{	

				//美达用户拥有
				if($visitUser_id)
				{
					
					/*
						根据ID找用户名
					*/
					$tableModaUser = FLEA::getSingleton('Table_ModaUser');
					$visitUser = $tableModaUser->getUserByUserId($visitUser_id);
					
			
					if($userName == $visitUser['username'])
					{
						return true;
					}
					else
						return false;
				}
			
				return true;
			
			}

	}	

	
	function actionHaibian(){
		
		include(APP_DIR . '/View/md_hanghai.html');
		
	}
	
	
	
	
	

}
?>
