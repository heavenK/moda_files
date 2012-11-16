<?php

FLEA::loadClass('Controller_BoBase');
class Controller_InterfaceForAndroid extends Controller_BoBase
{


	function Controller_InterfaceForAndroid(){
	    return ;
	}
	//轮播
    function actionLunbo(){
			$tableModaDoor = FLEA::getSingleton('Table_ModaDoor');	
			$doorDat = $tableModaDoor->IndexTest();
			$data_arrary['count'] = 4;
			$data_arrary['message'] = '';
			for($i = 1; $i<=4; $i++){
				$data_arrary['data'][$i-1]["sort"] = $i;
				$data_arrary['data'][$i-1]["url"] = "http://moda.we54.com/".$doorDat['img_'.$i];
				$data_arrary['data'][$i-1]["target"] = $doorDat['img'.$i.'_link'];
			}
			echo json_encode($data_arrary);
	}
	
	//美达展示
    function actionShowlist(){
		$tableModaUser = FLEA::getSingleton('Table_ModaUser');
		$tableModaShow = FLEA::getSingleton('Table_ModaShows');		
		if($_GET["sort"])
			$sort = (int)$_GET['sort'];
		else
			$sort = 1;
		$pagesize	=	12;
		$conditions	=	"available = 1 and public = 1";
		if($sort == 1)
			$sortby		=	"show_id DESC";
		if($sort == 2)
			$sortby		=	"views DESC";
		if($sort == 3)
			$sortby		=	"discuss_count DESC";
		FLEA::loadClass('Lib_Pager');
		FLEA::loadClass('Lib_ModaPager2');
		$page	=	new Lib_ModaPager2( $tableModaShow, $pagesize, $conditions , $sortby );
		$rowset	=	$page->rowset;
		$temp = 0;
		foreach($rowset as $dat)
		{
				$nickname = $tableModaUser->getUserByUserId($dat['user_id']);
				$rowset[$temp]['nickname'] = $nickname['nickname'];	
				$temp ++;
		}
		$data_arrary['page_count'] = $page->pageCount;
		$data_arrary['page_index'] = $page->currentPage;
		$data_arrary['sort'] = $sort;
		$i = 0;
		foreach($rowset as $v){
			$data_arrary['data'][$i]["album_id"] = $v['show_id'];
			$data_arrary['data'][$i]["belle_id"] = $v['user_id'];
			$data_arrary['data'][$i]["image_url"] = "http://moda.we54.com/".$v['show_img'];
			$data_arrary['data'][$i]["title"] = iconv('gbk','utf-8',$v['nickname'].'-'.$v['title']);
			$data_arrary['data'][$i]["browse_number"] = $v['views'];
			$data_arrary['data'][$i]["ablum_url"] = "http://moda.we54.com/?Controller=InterfaceForAndroid&action=Showpage&show_id=".$v['show_id'];
			$i++;
		}
		echo json_encode($data_arrary);
				
	}
	
	//图片展示
    function actionShowpage(){
		$show_id = (int)$_GET['show_id'];
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$showDE = $tableModaShows->getShowByShowId($show_id);
		$AttachmentsData	=	array();//附件表
		$tableModaAttach = FLEA::getSingleton('Table_ModaAttachments');
		$AttachmentsData = $tableModaAttach->getAttachAllByShowId($show_id);
		$row = $tableModaShows->getShowByShowId($show_id);
		$row['views']+=3;
		$tableModaShows->updatetar($row);
		if($showDE['main'] == 1){
			foreach($AttachmentsData as $data){
				if((int)$data['atc_type'] == 3){
					if(!empty($data['img_url'])){
						$url = $this->_UbbCover(stripslashes($data['img_url']));
					}else{
						$url = $this->_UbbCover(stripslashes($data['content']));
					}
				}
			}
			$data_arrary['is_offcial'] = true;
			$data_arrary['video_image'] = '';
			$data_arrary['video_url'] = $url;
		}
		else{
			$data_arrary['is_offcial'] = false;
			$data_arrary['video_image'] = '';
			$data_arrary['video_url'] = '';
		}
		$i = 0;
		foreach($AttachmentsData as $data){
			if((int)$data['atc_type'] == 2){
				$data_arrary['data'][$i]["image"] = "http://moda.we54.com/".$data['img_url'];
				$i++;
			}
		}
		echo json_encode($data_arrary);
	}

	//访谈列表	
	function actionVCRlist(){
	    $table = FLEA::getSingleton('Table_ModaNews');

		$pagesize	=	12;
		$conditions	=	"";

		if($_GET["sort"])
			$sort = (int)$_GET['sort'];
		else
			$sort = 1;

		if($sort == 1)
			$sortby="news_id desc";

		FLEA::loadClass('Lib_Pager');
		FLEA::loadClass('Lib_ModaPager2');
		$page	=	new Lib_ModaPager2( $table, $pagesize, $conditions , $sortby );
		$rowset	=	$page->rowset;
		$temp = 0;

		$jsonData['page_count']=$page->pageCount;
		$jsonData['page_index']=$page->currentPage;
		$jsonData['sort']=$sort;
		$a=0;
		foreach($rowset as $key=>$value){
			$jsonData['data'][$a]['id']=$value['news_id'];
			$jsonData['data'][$a]['image_url']="http://moda.we54.com/".$value['img_url'];
			$jsonData['data'][$a]['title']=iconv('gbk','utf-8',$value['news_title']);
			$jsonData['data'][$a]['starring']=iconv('gbk','utf-8',$value['news_st']);
			$jsonData['data'][$a]['video_url']=$value['content'];
			$a++;
		}
		echo json_encode($jsonData);
	}

	//首页大图list
	function actionLankList(){
		if(empty($_REQUEST['flag'])){
			echo("error");
			exit();
		}
		$flag=trim($_REQUEST['flag']);

	    $tableModaRanks = FLEA::getSingleton('Table_ModaRanks');
		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
		$tableModaShow = FLEA::getSingleton('Table_ModaShows');	

		$pagesize	=	12;

		FLEA::loadClass('Lib_Pager');
		FLEA::loadClass('Lib_ModaPager2');
		
		$flag=(int)$flag;

		$limit=" limit ".($flag-1).",1";

		$quarter_count=$tableModaRanks->findCount();

		$rowset = $tableModaRanks->findAll("","rank_id ASC".$limit);
		
		$jsonData=array();

		$a=0;
		foreach($rowset as $key=>$value){
			$conditions	=	"rank_id = ".$value['rank_id']."";
			$sortby = "Id DESC";
			$page	=	new Lib_ModaPager2( $tableModaRankShow, $pagesize, $conditions , $sortby );
			$ShowList	=	$page->rowset;
			$jsonData['page_count']=$page->pageCount;
			$jsonData['page_index']=$page->currentPage;
			$jsonData['quarter_count']=$quarter_count;
			$jsonData['quarter_index']=$flag;
			$tnum=$this->_NtoC($flag);
			$jsonData['title']='第'.$tnum.'季';

			foreach($ShowList as $v){
				$jsonData['data'][$a]['belle_id']=$v['user_id'];
				$jsonData['data'][$a]['album_id']=$v['show_id'];
				$showDat = $tableModaShow->getShowByShowIdNoLimit($v['show_id']);
				$jsonData['data'][$a]['image_url']="http://moda.we54.com/".$showDat['show_img'];
				$jsonData['data'][$a]['browse_number']=$showDat['views'];
				$jsonData['data'][$a]['name']=iconv('gbk','utf-8',$v['title']);
				$jsonData['data'][$a]['ablum_url']="http://moda.we54.com/?Controller=InterfaceForAndroid&action=Showpage&show_id=".$v['show_id'];
				$a++;
			}
			
		}
		echo json_encode($jsonData);
	}

	//个人相册
	function actionPersonList(){
	    $id = (int)$_GET['id'];

		$table = FLEA::getSingleton('Table_ModaUser');
		$result = $table->find("user_id= ".$id." And(pass = 1 or pass = 11)");
		$curUsername = $result['username'];
		$tableModaShows = FLEA::getSingleton('Table_ModaShows');
		$condition=' ';
		$showData = $tableModaShows->findALL("username = '".$curUsername."'");
		$jsonData=array();
		$jsonData['message']='';
		$a=0;
		foreach($showData as $data){
			$jsonData['data'][$a]['id']= $data['show_id'];
			$jsonData['data'][$a]['title']= iconv('gbk','utf-8',$data['title']);
			$jsonData['data'][$a]['browse_number']=$data['views'];
			$jsonData['data'][$a]['image_url']="http://moda.we54.com/".$data['show_img'];
			$jsonData['data'][$a]['ablum_url']="http://moda.we54.com/?Controller=InterfaceForAndroid&action=Showpage&show_id=".$data['show_id'];
			$a++;
		}
		echo json_encode($jsonData); 
	}
	//个人信息
	function actionPersonInfo(){
		if((int)$_GET['id']=="")
		{
			echo("error");
			exit();
		}
	    $Table_ModaUserExt = FLEA::getSingleton('Table_ModaUserExt');
		$userext = $Table_ModaUserExt->find("user_id = ".(int)$_GET['id']);
		$table = FLEA::getSingleton('Table_ModaUser');
		$result = $table->find("user_id = '".(int)$_GET['id']."' And (pass = 1 or pass = 11)");
		$tableModaRankShow = FLEA::getSingleton('Table_ModaRankShow');
		$rankshow = $tableModaRankShow->find("user_id = ".(int)$_GET['id']);
		
		if($rankshow['rank_id']!=7){
			foreach($userext as $extkey => $extval){
				$userext[$extkey]=stripslashes($extval);
			}
			$jsonData=array('belle_id'=>$userext['user_id'],'image_url'=>"http://moda.we54.com/".$result['head_img'],'basics'=>array(),'cover'=>array(),'moda_send_word'=>array());
			
			array_push($jsonData['basics'],array('title'=>'姓名','content'=>iconv('gbk','utf-8',$userext['xingming'])));
			array_push($jsonData['basics'],array('title'=>'体重','content'=>$userext['tizhong']));
			array_push($jsonData['basics'],array('title'=>'身高','content'=>$userext['shengao']));
			array_push($jsonData['basics'],array('title'=>'兴趣','content'=>iconv('gbk','utf-8',$userext['xingqu'])));
			array_push($jsonData['basics'],array('title'=>'三围','content'=>iconv('gbk','utf-8',$userext['sanwei'])));
			array_push($jsonData['basics'],array('title'=>'血型','content'=>iconv('gbk','utf-8',$userext['xuexing'])));
			array_push($jsonData['basics'],array('title'=>'星座','content'=>iconv('gbk','utf-8',$userext['xingzuo'])));
			array_push($jsonData['basics'],array('title'=>'职业','content'=>iconv('gbk','utf-8',$userext['zhiye'])));
			array_push($jsonData['basics'],array('title'=>'偶像','content'=>iconv('gbk','utf-8',$userext['ouxiang'])));
			array_push($jsonData['basics'],array('title'=>'特长','content'=>iconv('gbk','utf-8',$userext['techang'])));

			array_push($jsonData['cover'],array('question'=>'最想去哪旅游','answer'=>iconv('gbk','utf-8',$userext['ext_1'])));
			array_push($jsonData['cover'],array('question'=>'觉得自己最吸引异性的地方','answer'=>iconv('gbk','utf-8',$userext['ext_2'])));
			array_push($jsonData['cover'],array('question'=>'哪一类型的男生比较吸引你','answer'=>iconv('gbk','utf-8',$userext['ext_3'])));
			array_push($jsonData['cover'],array('question'=>'会如何对待自己讨厌的人','answer'=>iconv('gbk','utf-8',$userext['ext_4'])));
			array_push($jsonData['cover'],array('question'=>'一辈子都不会忘记的事','answer'=>iconv('gbk','utf-8',$userext['ext_5'])));
			array_push($jsonData['cover'],array('question'=>'一个人的时候常常会做什么？','answer'=>iconv('gbk','utf-8',$userext['ext_6'])));
			array_push($jsonData['cover'],array('question'=>'观察一个人时，你最先注意TA哪一点？为什么？','answer'=>iconv('gbk','utf-8',$userext['ext_7'])));
			array_push($jsonData['cover'],array('question'=>'最喜欢哪个美达会员？理由是什么？','answer'=>iconv('gbk','utf-8',$userext['ext_8'])));
			
			$jsonData['moda_send_word']=iconv('gbk','utf-8',$userext['meidajiyu']);
		}else{
			foreach($result as $rkey=>$rval){
				$result[$rkey]=stripslashes($rval);
			}
			$jsonData=array('belle_id'=>$result['user_id'],'image_url'=>"http://moda.we54.com/".$result['head_img'],'basics'=>array(),'cover'=>array(),'moda_send_word'=>array());
			
			array_push($jsonData['basics'],array('title'=>'姓名','content'=>iconv('gbk','utf-8',$result['truename'])));
			array_push($jsonData['basics'],array('title'=>'体重','content'=>$result['weight']));
			array_push($jsonData['basics'],array('title'=>'身高','content'=>$result['height']));
			array_push($jsonData['basics'],array('title'=>'兴趣','content'=>iconv('gbk','utf-8',$result['xingqu'])));
			array_push($jsonData['basics'],array('title'=>'三围','content'=>iconv('gbk','utf-8',$result['chestmeasurement']).'/'.iconv('gbk','utf-8',$result['waistline']).'/'.iconv('gbk','utf-8',$result['hip']).' CM'));
			array_push($jsonData['basics'],array('title'=>'血型','content'=>iconv('gbk','utf-8',$result['bloodtype'])));
			array_push($jsonData['basics'],array('title'=>'星座','content'=>iconv('gbk','utf-8',$result['starsings'])));
			array_push($jsonData['basics'],array('title'=>'偶像','content'=>iconv('gbk','utf-8',$result['likestar'])));
			array_push($jsonData['basics'],array('title'=>'喜爱食物','content'=>iconv('gbk','utf-8',$result['wish_hope'])));
			array_push($jsonData['basics'],array('title'=>'喜爱颜色','content'=>iconv('gbk','utf-8',$result['lovecolor'])));

			array_push($jsonData['cover'],array('question'=>'座右铭','answer'=>iconv('gbk','utf-8',$result['motto'])));
			array_push($jsonData['cover'],array('question'=>'最能让你兴奋的事情','answer'=>iconv('gbk','utf-8',$result['exct_things'])));
			array_push($jsonData['cover'],array('question'=>'无聊时大多都做些什么','answer'=>iconv('gbk','utf-8',$result['res_do'])));
			array_push($jsonData['cover'],array('question'=>'觉得快乐属于什么样的人','answer'=>iconv('gbk','utf-8',$result['my_opinion'])));
			array_push($jsonData['cover'],array('question'=>'最想对新青年网友说的话','answer'=>iconv('gbk','utf-8',$result['some_words'])));
			
			$jsonData['moda_send_word']=iconv('gbk','utf-8',$result['announcement']);
		}
		echo(json_encode($jsonData));
	}

	function _UbbCover($str)
	{
			FLEA::loadClass('Lib_Ubb');
			$Ubb = new Lib_Ubb();
			$Ubb->setString($str);
			$str2 = $Ubb->parse();
			return $str2;
	}
		
	function _NtoC($num){
		$d = array('零','一','二','三','四','五','六','七','八','九');
		$len=strlen($num);
		if($len<1){
			return "error";
			exit();
		}elseif($len<2){
			return $d[$num];
		}else{
			$numarr=explode('',$num);
			$realnum='';
			foreach($numarr as $key=>$value){
				$realnum.=$d[$value];
				if($key==1){
					$realnum.='十';
				}elseif($key==2){
					$realnum.='百';
				}
			}
			return $realnum;
		}
	    
	}	
	
	
	
}
?>
