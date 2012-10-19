<?php

class Controller_BoBase extends FLEA_Controller_Action
{
    /**
     * 构造函数
     *
     * 负责根据用户的 session 设置载入语言文件
     *
     * @return Controller_BoBase
     */
    function Controller_BoBase() {
       /* if (isset($_SESSION['LANG'])) {
            $lang = $_SESSION['LANG'];
            $languages = FLEA::getAppInf('languages');
            if (in_array($lang, $languages, true)) {
                FLEA::setAppInf('defaultLanguage', $lang);
            }
        }*/
        //load_language('ui, exception');
		$this->NAV	=		$this->_getNav("html");
    }


    /**
     * 返回用 _setBack() 设置的 URL
     */
    function _goBack() {
        $url = $this->_getBack();
        unset($_SESSION['BACKURL']);
        redirect($url);
    }

    /**
     * 设置返回点 URL，稍后可以用 _goBack() 返回
     */
    function _setBack() {
        $_SESSION['BACKURL'] = encode_url_args($_GET);
    }

    /**
     * 获取返回点 URL
     *
     * @return string
     */
    function _getBack() {
        if (isset($_SESSION['BACKURL'])) {
            $url = $this->rawurl($_SESSION['BACKURL']);
        } else {
            $url = $this->_url();
        }
        return $url;
    }

    /**
     * 直接提供查询字符串，生成 URL 地址
     *
     * @param string $queryString
     *
     * @return string
     */
    function rawurl($queryString) {
    	if (substr($queryString, 0, 1) == '?') {
    		$queryString = substr($queryString, 1);
    	}
    	return $_SERVER['SCRIPT_NAME'] . '?' . $queryString;
    }
		
	//取得文件类型
	function _getfiletype($filename){	
		$f	=	strrev($filename);
		$f = explode(".", $f);
		return strrev($f[0]);
	}	
		

	
	function _alert($word="",$url="",$target="self"){
		if($_REQUEST["forward"]!=''){
			$url	=	$_REQUEST["forward"];
		}elseif($url==""){
			$url	=	$_SERVER['HTTP_REFERER'];
		}
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');$target.location='$url';</script>
</head><body></body></html>";
	}	
	
	function _isPost(){
		return count($_POST)>0;
	}
	//function _isGet(){
//		return count($_GET)>0;
//	}
	
	function _getNav($which){
		$nav	=	array(
						array(
							"title"	=>"首   页",
							"link"	=>"index.php",
							"sons"	=>array(),
						),
						array(
							"title"	=>"新闻公告",
							"link"	=>"index.php?controller=Default&action=ListNews",
							"sons"	=>array(
										array(
											"title"	=>"学校公告",
											"link"	=>""
											),
										array(
											"title"	=>"学校公告",
											"link"	=>""
										),
									  ),
						),
						array(
							"title"	=>"教师频道",
							"link"	=>"",
							"sons"	=>array(
										array(
											"title"	=>"考勤查询",
											"link"	=>"index.php?controller=Default&action=",
											),
										array(
											"title"	=>"考勤时段",
											"link"	=>"index.php?controller=Default&action=",
										),
									  ),
						),
						array(
							"title"	=>"学生频道",
							"link"	=>"",
							"sons"	=>array(
										array(
											"title"	=>"考勤查询",
											"link"	=>"",
											),
										array(
											"title"	=>"成绩查询",
											"link"	=>"",
											),
										array(
											"title"	=>"考勤时段",
											"link"	=>"",
											),
									  ),
						),
						array(
							"title"	=>"课件中心",
							"link"	=>"",
							"sons"	=>array(),
						),
						array(
							"title"	=>"用户系统",
							"link"	=>"index.php?controller=Admin",
							"sons"	=>array(),
						),
					);
		if($which=="html"){
			$html	=	"";
			foreach($nav as $key=>$val){
				$html.=	'<li><a href="'.$val['link'].'" class="top">'.$val['title'].'</a></li>';
				if(count($val['sons'])>0){
					$html.='<ul>';
					foreach($val['sons'] as $k=>$v){
						$html.=	'<li><a href="'.$v['link'].'">'.$v['title'].'</a></li>';
					}
					$html.='</ul>';
				}
			}
			return $html;
		}else{
			return $nav;
		}
		
	}
	
	public function _include($name){
		include(APP_DIR . '/Fview/header.html');
		include(APP_DIR . '/Fview/'.$name);
		include(APP_DIR . '/Fview/footer.html');		
	}
	
/*	public function _authcode(){
		 $type='png';
		 $width=50;
		 $height=20;
		 srand((double)microtime()*1000000);
		 
		 $randval = sprintf("%04d", rand(0,9999));
		 Header("Content-type: image/".$type);
		 
		if ( $type!='gif' && function_exists('imagecreatetruecolor')) { 
		$im = @imagecreatetruecolor($width,$height);
		 }else { 
		  $im = @imagecreate($width,$height);
		 }
		 $r = Array(225,255,255,223);
		 $g = Array(225,236,237,255);
		 $b = Array(225,236,166,125);
		 
		 $key = rand(0,3);
		  
		 $backColor = ImageColorAllocate($im, $r[$key],$g[$key],$b[$key]);//背景色（随机）
		 $borderColor = ImageColorAllocate($im, 0, 0, 0);//边框色
		 $pointColor = ImageColorAllocate($im, 0, 255, 255);//点颜色
		 
		 @imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		 @imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
		 $stringColor = ImageColorAllocate($im, 255,51,153);
		 for($i=0;$i<=10;$i++){ 
		  $pointX = rand(2,$width-2);
		  $pointY = rand(2,$height-2);
		  @imagesetpixel($im, $pointX, $pointY, $pointColor);
		 }
		 
		 @imagestring($im, 5, 8, 3, $randval, $stringColor);
		 $ImageFun='Image'.$type;
		 $ImageFun($im);
		 @ImageDestroy($im);
        
        $_SESSION["authcode"] = $randval; 
        die(); 	
	}*/
	
	function _csv($body=array(),$head=array()){
        
		if(count($head)>0){$csv	=	implode(',',$head)."\r\n";}
             
        foreach($body as $key=>$val){
	        foreach($val as $k=>$v){
               $csv.=$v.",";
            }
			$csv = rtrim($csv,',');
            $csv.="\r\n";
        }
		return $csv;
	
	}
	
	function _convertarr($arr){
		$csv='';
		$i=0;
		foreach($arr as $val){
			$i++;
			if($i%10!=0){
				$csv	.=	"(".$val['banjimingcheng'].")".$val['xueshengxingming'].",";
			}else{
				$csv	.=	$val['xueshengxingming']."\r\n";			
			}		
		}
		return $csv;
	}
	
	
}
