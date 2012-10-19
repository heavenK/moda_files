<?php

FLEA::loadClass('Controller_BoBase');
class Controller_Index extends Controller_BoBase
{
    /**
     * ��ʾ����
     */	 
    function actionIndex(){

		$jg	=	FLEA::getSingleton('Table_Polls');
		if($this->_isPost()){
			////��ֹ����
			$this->_alert("ͶƱ��ֹ��","http://moda.we54.com");exit();
			/*kaishi*/
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
			
			$table = FLEA::getSingleton('Table_Pollips');	
			$tnum	=	count($_POST['poll_id']);
			if(!$table->checkip()){
				$this->_alert("һ��IPһ��ֻ��Ͷһ�Σ�");exit();
			}
			if($tnum!=10){
				$this->_alert("����ѡ��10����ѡ�ˣ����ܶ�Ҳ�����٣�".$tnum);exit();
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
			$rowset	=	$jg->findAll('','cixu DESC');	
			//dump($rowset);
			$usertable		=	FLEA::getSingleton('Table_Cdbmembers');
			$currentuser	=	$usertable->_getUserByUsername($_SESSION["Zend_Auth"]['storage']->username);
			include(APP_DIR . '/View/index.html');		
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
				include(APP_DIR . '/View/pic.html');
			}else{
				$this->_alert("���޴��ˣ��Ƿ�����");exit();
			}			
		}else{
			$this->_alert("�Ƿ�����");	exit();
		}			
	}
	
	function actionResult(){
		
		include(APP_DIR . '/View/result.html');
				
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
		header(" charset=utf-8");
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
	

}
?>
