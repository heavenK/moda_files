<?php
/**
 * 
 *
 * FLEA_Helper_Pager ʹ�úܼ򵥣�ֻ��Ҫ����ʱ���� FLEA_Db_TableDataGateway ʵ���Լ���ѯ�������ɡ�
 *
 * @package Core
 * @author ��Դ�Ƽ� (www.qeeyuan.com)
 * @version 1.0
 */
class Lib_AuthCode
{
	function checkCode($code){
		if($_SESSION['validate']==$code){
			return true;
		}else{
			return false;
		}
	}
		
	function genImg(){
	/*
ת����ע����Դ�� ITBBS ��
�޸�����ϵ�������䣺Smartly@itbbs.cn
*/
		Header("Content-type: image/PNG");
		session_start();
		$str = "��ǻӾഥ�����ͻ��˶��ٻ����δͻ��կ����ʪƫ�Ƴ�ִ����������Э���޶�Ӳ��Ŭ�����Ԥְ�������ֻ���ì������ٸ�������������ͣ����Ӫ�ո���Ǯ��������ɳ�˳��ַ�е�ذ����İ��������۵��յ���ѽ�ʰɿ��ֽ�������������ĩ�����ӡ�伱�����˷�¶��Ե�������������Ѹ��������ֽҹ������׼�����ӳ��������ɱ���׼辧�尣ȼ��������ѿ��������̼��������ѿ����б��ŷ��˳������͸˾Σ������Ц��β��׳��������������ţ��Ⱦ�����������Ƽ�ֳ�����ݷô���ͭ��������ٺ����úӭ��ճ̽�ٱ��Դ��ظ���϶¯����Ѯ�Ƹ�������Ը���������̾䴿������������³�෱�������׶ϣ�ذܴ�����ν�л��ܻ���ڹ��ʾ����ǳ���������Ϣ������������黭�������������躮ϲ��ϴʴ���ɸ���¼������֬ׯ��������ҡ�������������Ű²��ϴ�;��ڵ�һ�����˲����д�������֧ʶ�������Ϲ�רʲ���;�ʾ������ÿ�����������Ϲ����ֿƱ�������Ƹ��������������༯������װ����֪���е�ɫ����ٷ�ʷ��������������ȷ������״������֯�������󴫿ڶϿ��ɾ����Ʒ�вβ�ֹ������Ŀ����Ȩ�Ҷ����֤��Խ���Թ�˹��ע�����������ر��̳�������ǧʤϸӰ�ð׸�Ч���ƿ��䵶Ҷ������ѡ���»������ʼƬʩ���ջ�������������ҩ����Ѵ��ʿ���Һ��׼��ǽ�ά�������������״����ƶ˸������ش幹���ݷǸ���ĥ�������ʽ���ֵ��̬���ױ�������������̨���û������ܺ���ݺ����ʼ��������Ͼ��ݼ���ҳ�����Կ�Ӣ�ƻ���Լ�Ͳ�ʡ���������ӵ۽�����ֲ��������������ץ���縱����̸Χʳ��Դ�������ȴ����̻����������׳߲��зۼ������濼�̿�������ʧ��ס��֦�־����ܻ���ʦ������Ԫ����ɰ�⻻̫ģƶ�����ｭ��Ķľ����ҽУ���ص�����Ψ�们վ�����ֹĸ�д��΢�Է�������ĳ�����������൹�������ù�Զ��Ƥ����ռ����Ȧΰ��ѵ�ؼ��ҽ��ƻ���������ĸ�����ֶ���˫��������ʴ����˿Ůɢ��������Ժ�䳹����ɢ�����������������Ѫ��ȱ��ò�����ǳ���������������̴���������������Ͷ��ū������Ϊ�ǵظ��ù�ʱҪ���������ҵ�����������������ѧ�¼�������巢�ɲ���ɳ��ܷ���ͬ����˵�ֹ����ȶ����Ӻ������С��Ҳ�����߱������������ʵ�Ҷ������ˮ������������������ʮս��ũʹ��ǰ�ȷ���϶�·�ѽ�������¿���֮��ӵ���Щ�������¶�����Ӧ�����������ɶ����ص�������˼�����ȥ�����������ѹԱ��ҵ��ȫ�������ڵ�ƽ��������ëȻ�ʱ�չ�������û���������ϵ������Ⱥͷֻ���ĵ����ϴ���ͨ�����Ͽ��ֹ�����������ϯλ����������ԭ��Ū�ҿ�����ʢ��Ԯ���Ǽ���������Ħæ������������ָ��������ںܽ̾��ش˳�ʯǿ�����Ѹ���ֱ��ͳʽת�����о���ȡ������������־�۵���ôɽ�̰ٱ��������汣��ί�ָĹܴ���������Ұ�ž�ı���˽����������������Ʊܷ�������Ƶ���Ҹ�ŵ����Ũ��Ϯ˭��л�ڽ���Ѷ���鵰�պ������ͽ˽������̹����ù�����ո��伨���ܺ���ʹ�����������ж�����׷���ۺļ���������о�Ѻպ��غ���Ĥƪ��פ������͹�ۼ���ѩ�������������߲��������ڽ������˹�̿������������ǹ���ð������Ͳ���λ�����Ϳζ����Ϻ�½�����𶹰�Ī���¾����۱�����ɶ���ܼ��Ժ��濵�������԰ǻ�����������������������ƭ�ݽ赤�ɶ��ٻ���ϡ��������ǳӵѨ������ֽ����������Ϸ��������ò�����η��ɰ���������ˢ�ݺ���������©�������Ȼľ��з������Բ����ҳ�����ײ����ȳ����ǵ������������ɨ������оү���ؾ����Ƽ��ڿ��׹��ð����ף�������������ݴ���ι�������Ź�ó����ǽ���˽�ī������ж����ڱ������������������𾪶ټ�����ķ��ɭ��ʥ���մʳٲ��ھ�";
		$imgWidth = 140;
		$imgHeight = 40;
		$authimg = imagecreate($imgWidth,$imgHeight);
		$bgColor = ImageColorAllocate($authimg,255,255,255);
		$caonima	=	rand(1,5);
		$fontfile = $caonima.".ttf";
		$white=imagecolorallocate($authimg,234,185,95);
		imagearc($authimg, 150, 8, 20, 20, 75, 170, $white);
		imagearc($authimg, 180, 7,50, 30, 75, 175, $white);
		imageline($authimg,20,20,180,30,$white);
		imageline($authimg,20,18,170,50,$white);
		imageline($authimg,25,50,80,50,$white);
		$noise_num = 500;
		$line_num = 10;
		imagecolorallocate($authimg,0xff,0xff,0xff);
		$rectangle_color=imagecolorallocate($authimg,0xAA,0xAA,0xAA);
		$noise_color=imagecolorallocate($authimg,0x00,0x00,0x00);
		
		$temp = $this->RgbToHsv(rand(0, 255), rand(0, 255), rand(0, 255));
		
		if($temp[2] < 200){
		 $temp [2] = 255;
		 }		 
		$temp = $this->HsvToRgb($temp[0], $temp[1], $temp[2]);
		$line_color = imagecolorallocate($authimg, $temp[0], $temp[1], $temp[2]);
		
		
		$font_color=imagecolorallocate($authimg,0x00,0x00,0x00);
		//$line_color=imagecolorallocate($authimg,0x00,0x00,0x00);
		for($i=0;$i<$noise_num;$i++){
		imagesetpixel($authimg,mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),$noise_color);
		}
		for($i=0;$i<$line_num;$i++){
		imageline($authimg,mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),$line_color);
		}
		$randnum=rand(0,strlen($str)-4);
		if($randnum%2)$randnum+=1;
		$str = substr($str,$randnum,6);
		$_SESSION['validate']	=	$str;	
		$str = iconv("GB2312","UTF-8",$str);		
		ImageTTFText($authimg, 26, 6, 5, 36, $font_color, $fontfile, $str);
		ImagePNG($authimg);
		ImageDestroy($authimg);
		//header("Pragma: no-cache");
//		header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate ;charset=utf-8");
//		session_start();
//		unset($_SESSION['validate']);
//		
//		$randStr = array(rand(0, 9), rand(0, 9), rand(0, 9), iconv( "gb2312", "UTF-8//IGNORE" , "��"));  // ����4������ַ�
//		$_SESSION['validate'] = $randStr[0].$randStr[1].$randStr[2].$randStr[3];
//		
//		$size = 20;
//		$width = 80;
//		$height = 25;
//		$degrees = array(rand(0, 45), rand(0, 45), rand(0, 45), rand(0, 45)); // ����������ת�Ƕ�
//		
//		for($i = 0; $i < 4; ++$i)
//		{
//		 if(rand() % 2);
//		 else $degrees[$i] = -$degrees[$i];
//		}
//		
//		$image = imagecreatetruecolor($size, $size);   // ����ͼƬ����
//		$validate = imagecreatetruecolor($width, $height);  // ������֤�뻭��
//		$back = imagecolorallocate($image, 255, 255, 255);  // ����ɫ
//		$border = imagecolorallocate($image, 0, 0, 0);    // �߿�
//		imagefilledrectangle($validate, 0, 0, $width, $height, $back); // ��������ɫ
//		
//		// ������ɫ
//		for($i = 0; $i < 4; ++$i)
//		{
//		 // ����Ϊʹ�ַ����׿���ʹ����ɫ�ϰ�����ɫ
//		 $temp = $this->RgbToHsv(rand(0, 255), rand(0, 255), rand(0, 255));
//		 
//		 if($temp[2] > 60)
//		  $temp [2] = 60;
//		
//		 $temp = $this->HsvToRgb($temp[0], $temp[1], $temp[2]);
//		 $textcolor[$i] = imagecolorallocate($image, $temp[0], $temp[1], $temp[2]);
//		}
//		
//		for($i = 0; $i < 200; ++$i) //�����������
//		{
//		 $randpixelcolor = ImageColorallocate($validate, rand(0, 255), rand(0, 255), rand(0, 255));
//		 imagesetpixel($validate, rand(1, 87), rand(1, 27), $randpixelcolor);
//		}
//		
//		// ������ʹ����ɫ����������ɫ
//		$temp = $this->RgbToHsv(rand(0, 255), rand(0, 255), rand(0, 255));
//		
//		if($temp[2] < 200)
//		 $temp [2] = 255;
//		 
//		$temp = $this->HsvToRgb($temp[0], $temp[1], $temp[2]);
//		$randlinecolor = imagecolorallocate($image, $temp[0], $temp[1], $temp[2]);
//		
//		// ��5��������
//		for ($i = 0;$i < 5; $i ++)
//		 imageline($validate, rand(1, 79), rand(1, 24), rand(1, 79), rand(1, 24), $randpixelcolor);
//		
//		imagefilledrectangle($image, 0, 0, $size, $size, $back); // ��������ɫ 
//		imagestring($image, 5, 6, 2, $randStr[0], $textcolor[0]);  // ��������
//		$image = imagerotate($image, $degrees[0], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 1, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		$image = imagecreatetruecolor($size, $size); // ˢ�»���
//		imagefilledrectangle($image, 0, 0, $size, $size, $back);  // ��������ɫ 
//		imagestring($image, 5, 6, 2, $randStr[1], $textcolor[1]);  // ��������
//		$image = imagerotate($image, $degrees[1], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 21, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		$image = imagecreatetruecolor($size, $size); // ˢ�»���
//		imagefilledrectangle($image, 0, 0, $size - 1, $size - 1, $back);  // ��������ɫ 
//		imagestring($image, 5, 6, 2, $randStr[2], $textcolor[2]);  // ��������
//		$image = imagerotate($image, $degrees[2], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 41, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		$image = imagecreatetruecolor($size, $size); // ˢ�»���
//		imagefilledrectangle($image, 0, 0, $size - 1, $size - 1, $back);  // ��������ɫ 
//		imagestring($image, 5, 6, 2, $randStr[3], $textcolor[3]);  // ��������
//		$image = imagerotate($image, $degrees[3], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 61, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		imagerectangle($validate, 0, 0, $width - 1, $height - 1, $border);  // �����߿�
//		
//		header('Content-type: image/png');
//		imagepng($validate);
//		imagedestroy($validate);
//		imagedestroy($image);	
	}
   		

	function RgbToHsv($R, $G, $B)
	{
	 $tmp = min($R, $G);
	  $min = min($tmp, $B);
	  $tmp = max($R, $G);
	  $max = max($tmp, $B);
	  $V = $max;
	  $delta = $max - $min;
	
	  if($max != 0)
	   $S = $delta / $max; // s
	  else
	  {
	   $S = 0;
		//$H = UNDEFINEDCOLOR;
		return;
	  }
	  if($R == $max)
	   $H = ($G - $B) / $delta; // between yellow & magenta
	  else if($G == $max)
		$H = 2 + ($B - $R) / $delta; // between cyan & yellow
	  else
		$H = 4 + ($R - $G) / $delta; // between magenta & cyan
	
	  $H *= 60; // degrees
	  if($H < 0)
	   $H += 360;
	  return array($H, $S, $V);
	}

	function HsvToRgb($H, $S, $V)
		{
		 if($S == 0)
		  {
		   // achromatic (grey)
		   $R = $G = $B = $V;
			return;
		  }
		
		  $H /= 60;  // sector 0 to 5
		  $i = floor($H);
		  $f = $H - $i;  // factorial part of h
		  $p = $V * (1 - $S);
		  $q = $V * (1 - $S * $f);
		  $t = $V * (1 - $S * (1 - $f));
		
		  switch($i)
		  {
		   case 0:
			 $R = $V;
			  $G = $t;
			  $B = $p;
			  break;
			case 1:
			  $R = $q;
			  $G = $V;
			  $B = $p;
			  break;
			case 2:
			  $R = $p;
			  $G = $V;
			  $B = $t;
			  break;
			case 3:
			  $R = $p;
			  $G = $q;
			  $B = $V;
			  break;
			case 4:
			  $R = $t;
			  $G = $p;
			  $B = $V;
			  break;
			default: // case 5:
			  $R = $V;
			  $G = $p;
			  $B = $q;
			  break;
		 }
		  return array($R, $G, $B);
		}
	
}
