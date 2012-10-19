<?php

FLEA::loadClass('Controller_BoBase');
class Controller_Index3 extends Controller_BoBase
{
	//�ӿǺ���
	function _convertSpecialChars($str)
	{
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
						
						$str = htmlspecialchars($str);
					  
					  return $str;
				
	
	}


    /**
     * ��ʾ����
     */	 
    function actionIndex(){
	
		$jg	=	FLEA::getSingleton('Table_Polls');
		if($this->_isPost()){
			////��ֹ����
			//$this->_alert("ͶƱ��ֹ��","http://moda.we54.com");exit();
			/*kaishi*/
			if((time()-strtotime("2009-6-22 0:0:0"))>0){
				$this->_alert("ͶƱ��ֹ��","http://moda.we54.com");exit();
			}
			if($_SESSION["Zend_Auth"]['storage']->username==''){
				$this->_alert("��û�е�¼�����¼�����ͶƱ��","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));exit();
			}
			$usertable		=	FLEA::getSingleton('Table_Cdbmembers');
			$currentuser	=	$usertable->_getUserByUsername($_SESSION["Zend_Auth"]['storage']->username);
			if($currentuser['groupid']>3 and $currentuser['groupid']<=7){
				$this->_alert("���ִﵽ���������Ϸ��ɽ���ͶƱ��","http://moda.we54.com");exit();
			}
			if($currentuser['groupid']>=9 and $currentuser['groupid']<=31 and $currentuser['groupid']!=22){
				$this->_alert("���ִﵽ���������Ϸ��ɽ���ͶƱ��","http://moda.we54.com");exit();
			}
			var_dump($currentuser['groupid']);
			//echo $_SESSION["validate"].'--'.$_POST['validate'];
			//dump($_SESSION);
			$table = FLEA::getSingleton('Table_Pollips');	
			$tnum	=	count($_POST['poll_id']);
			if(!$table->checkip()){
				$this->_alert("һ��IPһ��ֻ��Ͷһ�Σ�");exit();
			}
			if($tnum!=1){
				$this->_alert("����ѡ��1����ѡ�ˣ����ܶ�Ҳ�����٣�".$tnum);exit();
			}
			
			//��֤��
			
			$ac = FLEA::getSingleton('Lib_AuthCode');
			if(!$ac->checkCode($_POST["validate"])){
				$this->_alert("��֤����������ԣ�");exit();
			}
			
			//echo "ss";
			//OK��Ʊ
			if($jg->vote($_POST['poll_id'])){
				$this->_alert("�ɹ�ͶƱ��");exit();
			}else{
				$this->_alert("����δ֪���������ԣ�");exit();
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
				$this->_alert("���޴��ˣ��Ƿ�����");exit();
			}			
		}else{
			$this->_alert("�Ƿ�����");	exit();
		}			
	}
	
	
	
	
	
	/*ע��
	*/
	
	function actionRegister()
	{
	
	
	
		if($_SESSION["Zend_Auth"]['storage']->username==''){
				$this->_alert("��û�е�¼�����¼���ύ���룡","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/?Controller=Index3&action=Register"));exit();
		}
		

		if($this->_isPost())
		{
				
				$modauser=FLEA::getSingleton('Table_ModaUser');	
				$modaresult=$modauser->find("username='".$_SESSION["Zend_Auth"]['storage']->username."'" );
				if($modaresult['pass'] == 1){
					//$this->_alert("���Ѿ��ύ��������Ϣ������������ˣ���ȴ�����������ϵ!~","http://moda.we54.com");exit();
					$this->_alert("���û�Ϊ�����û���������Ч!~","http://moda.we54.com");exit();
				}
					
				//��ȡ�ļ���С
				$size1 = $_FILES['img1']['size'];
				$size2 = $_FILES['img2']['size'];
				$size3 = $_FILES['img3']['size'];
				$size4 = $_FILES['img4']['size'];
				$size5 = $_FILES['img5']['size'];
				$size6 = $_FILES['img6']['size'];
				if($_FILES['img1']['tmp_name'] == '' ||$_FILES['img2']['tmp_name'] == '' ||$_FILES['img3']['tmp_name'] == '' ||$_FILES['img4']['tmp_name'] == '' ||$_FILES['img5']['tmp_name'] == '' ||$_FILES['img6']['tmp_name'] == '' ){
					$this->_alert("���ϴ�6����Ƭ");
					exit;
				}
				if($size1>1048576||$size2>1048576||$size3>1048576||$size4>1048576||$size5>1048576||$size6>1048576){
					$this->_alert("�����ϴ�ͼƬ���ܳ���1MB��");
					exit;
				}
				if($_POST['truename']==''||$_POST['bdate']==''||$_POST['mobile']==''){
					$this->_alert("��ȷ��������д����");
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
					$this->_alert('���ĸ�����Ϣ���ѳɹ���¼�����_������Ů�����Ͽ���,����ϱ�׼,���ǻ�������ϵ.��ӭ�������ע���_������Ů��,��ע�����꣡','http://moda.we54.com');				
				}
				else
				{
					$this->_alert('�ύ��Ϣ������������ϵ����Ա��','http://moda.we54.com');
				}
	
		}
		else
		{
				//include(APP_DIR . '/View/sqjr333.html');
				//moda �汾�л�
				if($_COOKIE['moda_version'] == 'version_2')
					include(APP_DIR."/".$_COOKIE['moda_version']."/sqjr333.html");
				elseif($_COOKIE['moda_version'] == 'version_1')
					include(APP_DIR."/View/sqjr333.html");
				else
					include(APP_DIR."/version_2/sqjr333.html");
		}		
		
	}
	
	
	
	
	
	//��֤�û�
	function actionRegCk(){
		if($_SESSION["Zend_Auth"]['storage']->username==''){
				$this->_alert("��û�е�¼�����¼���ύ���룡","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com/?Controller=Index3&action=Register"));exit();
		}
	}
	
	function actionResult(){
			include 'php-ofc-library/open-flash-chart.php';
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		//header('Content-type: text/plain');//���������

		header("Content-Type: text/html; charset=UTF-8");
		$jg	=	FLEA::getSingleton('Table_Polls');
		$rowset1	=	$jg->findAll();
		
		//$title = new title( date("D M d Y") );
		$title	=	new title( "���������������" );
		$title->set_style( '{color: #d070ac; font-size: 14px}' );
		
		
		
		$data=array();
		
		foreach($rowset1 as $val1){
			$bar = new bar_value($val1['votes']);
			$bar->set_tooltip( iconv( "gb2312", "UTF-8//IGNORE" , $val1['title'].'<br>Ʊ����'.$val1['votes']) );
			//$bar->set_tooltip( mb_convert_encoding($val1['title'].'<br>Ʊ����'.$val1['votes'], "UTF-8", "GBK") );
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
		//$x_labels->set_labels  ( '��Ů');
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
		//header('Content-type: text/plain');//���������

		header("Content-Type: text/html; charset=utf-8");
		$jg	=	FLEA::getSingleton('Table_Polls');
		$rowset1	=	$jg->findAll();
		
		//$title = new title( date("D M d Y") );
		$title	=	new title( "���������������" );
		$title->set_style( '{color: #d070ac; font-size: 14px}' );
		
		
		
		$data=array();
		
		foreach($rowset1 as $val1){
			$bar = new bar_value($val1['votes']);
			//$bar->set_tooltip( $val1['title'].'<br>Ʊ����'.$val1['votes']);
			$bar->set_tooltip("������");
			//$bar->set_tooltip( iconv( "gb2312", "UTF-8//IGNORE" , $val1['title'].'<br>Ʊ����'.$val1['votes']) );
			//$bar->set_tooltip( mb_convert_encoding($val1['title'].'<br>Ʊ����'.$val1['votes'], "UTF-8", "GBK") );
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
		//$x_labels->set_labels  ( '��Ů');
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
	����Ա�û�
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
����û�Ȩ��
*/
	function _getUserAmin($visitUser_id = ''){
			$userName = $_SESSION["Zend_Auth"]['storage']->username;
			//$userName = "4444";
			
			if($userName==''){
				//$this->_alert("��û�е�¼��","http://passport.we54.com/Index/login?forward=".urlencode("http://moda.we54.com"));exit();
				
				
				return false;
				
			}
			else
			{	

				//�����û�ӵ��
				if($visitUser_id)
				{
					
					/*
						����ID���û���
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
