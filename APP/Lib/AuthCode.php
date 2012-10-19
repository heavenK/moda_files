<?php
/**
 * 
 *
 * FLEA_Helper_Pager  π”√∫‹ºÚµ•£¨÷ª–Ë“™ππ‘Ï ±¥´»Î FLEA_Db_TableDataGateway  µ¿˝“‘º∞≤È—ØÃıº˛º¥ø…°£
 *
 * @package Core
 * @author ∆‘¥ø∆ºº (www.qeeyuan.com)
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
◊™‘ÿ«Î◊¢√˜¿¥‘¥”⁄ ITBBS °£
–ﬁ∏ƒ«Î¡™œµ◊˜’ﬂ–≈œ‰£∫Smartly@itbbs.cn
*/
		Header("Content-type: image/PNG");
		session_start();
		$str = "»Û∏«ª”æ‡¥•–«À…ÀÕªÒ–À∂¿πŸªÏºÕ“¿Œ¥Õªº‹’ØøÌ∂¨’¬ ™∆´Œ∆≥‘÷¥∑ßøÛ‘ ÏŒ»–≠À√ﬁ∂·”≤º€≈¨∑≠∆Êº◊‘§÷∞∆¿∂¡±≥«÷ª“À‰√¨∫Ò¬ﬁƒ‡±Ÿ∏Ê¬—œ‰’∆—ı∂˜∞ÆÕ£‘¯»‹”™÷’∏Ÿ√œ«Æ¥˝æ°∂ÌÀı…≥ÕÀ≥¬Ã÷∑‹–µ‘ÿ∞˚”◊ƒƒ∞˛∆»–˝’˜≤€µπŒ’µ£»‘—Ωœ ∞…ø®¥÷ΩÈ◊Í÷»ıΩ≈≈¬—Œƒ©“ı∑·±‡”°∑‰º±ƒ√¿©…À∑…¬∂∫À‘µ”Œ’Ò≤Ÿ—ÎŒÈ”Ú…ı—∏ª‘“Ï–Ú√‚÷Ω“πœÁæ√¡•∏◊º–ƒÓ¿º”≥πµ““¬»Â…±∆˚¡◊ºËæß≤Â∞£»ºª∂Ã˙≤π‘€—ø”¿Õﬂ«„’ÛÃº—›Õ˛∏Ω—¿—ø”¿Õﬂ–±π‡≈∑œ◊À≥÷Ì—Û∏Ø«ÎÕ∏ÀæŒ£¿®¬ˆ“À–¶»ÙŒ≤ ¯◊≥±©∆Û≤ÀÀÎ≥˛∫∫”˙¬Ã≈£∑›»æº»«Ô±È∂Õ”Òœƒ¡∆º‚÷≥æÆ∑—÷›∑√¥µ»ŸÕ≠—ÿÃÊπˆøÕ’Ÿ∫µŒÚ¥Ã√∫”≠÷˝’≥ÃΩ¡Ÿ±°ƒ‘¥Îπ·≤ÿ∏“¡Óœ∂¬Øø«¡Ú—Æ…∆∏£◊›‘Ò¿Ò‘∏∑¸≤–¿◊—”—Ãæ‰¥øΩ•∏˚≈‹‘Û¬˝‘‘¬≥≥‡∑±æ≥≥±∫·µÙ◊∂œ£≥ÿ∞‹¥¨ºŸ¡¡ŒΩÕ–ªÔ’‹ª≥∏Ó∞⁄π±≥ æ¢≤∆“«≥¡¡∂¬È◊Ô◊Êœ¢≥µ¥©ªıœ˙∆Î Û≥Èª≠À«¡˙ø‚ ÿ÷˛∑ø∏Ë∫Æœ≤∏Áœ¥ ¥∑œƒ…∏π∫ı¬ºæµ∏æ∂Ò÷¨◊Ø≤¡œ’‘ﬁ÷”“°µ‰±˙±Á÷Òπ»¬Ù–È«≈∞¬≤Æ∏œ¥πÕæ∂Ó±⁄µƒ“ª «‘⁄¡À≤ª∫Õ”–¥Û’‚÷˜–ﬁ÷ß ∂≤°œÛº∏œ»¿œπ‚◊® ≤¡˘–Õæﬂ æ∏¥∞≤¥¯√ø∂´‘ˆ‘ÚÕÍ∑Áªÿƒœπ„¿Õ¬÷ø∆±±¥Úª˝≥µº∆∏¯Ω⁄◊ˆŒÒ±ª’˚¡™≤Ω¿‡ºØ∫≈¡–Œ¬◊∞º¥∫¡÷™÷·—–µ•…´º·æ›ÀŸ∑¿ ∑¿≠ ¿…Ë¥Ô±ﬂ«Â÷¡ÕÚ»∑æø È ı◊¥≥ß∂˚≥°÷Ø¿˙ª® ‹«Û¥´ø⁄∂œøˆ≤…æ´ΩΩÁ∆∑≈–≤Œ≤„÷π–Î¿Î‘Ÿƒø∫£Ωª»®«“∂˘«‡≤≈÷§µÕ‘Ω∞À ‘πÊÀπΩ¸◊¢√≈Ã˙–Ë◊ﬂ“Èœÿ±¯πÃ≥˝∞„“˝≥›«ß §œ∏”∞º√∞◊∏Ò–ß÷√Õ∆ø’≈‰µ∂“∂¬  ˆΩÒ—°—¯µ¬ª∞≤È≤Ó∞Îµ– º∆¨ ©œÏ ’ª™æı±∏√˚∫Ï–¯æ˘“©±Íº«ƒ—¥Ê≤‚ ø…ÌΩÙ“∫≈…◊ºΩÔΩ«ΩµŒ¨∞Â–Ì∆∆ ˆººœ˚µ◊¥≤ÃÔ ∆∂À∏–Õ˘…Ò±„∫ÿ¥Âππ’’»›∑«∏„—«ƒ•◊Âª∂ŒÀ„  Ω≤∞¥÷µ√¿Ã¨ª∆“◊±Î∑˛‘Á∞‡¬Ûœ˜–≈≈≈Ã®…˘∏√ª˜Àÿ’≈√‹∫¶∫Ó≤›∫Œ ˜∑ ºÃ”“ Ù –—œæ∂¬›ºÏ◊Û“≥øπÀ’œ‘ø‡”¢≥∆ªµ“∆‘º∞Õ≤ƒ °∫⁄Œ‰≈‡÷¯∫”µ€Ωˆ’Î‘ı÷≤≤›ƒ„¬Ë…˝Õı—€À˝◊•∫¨√Á∏±‘”∆’Ã∏Œß ≥…‰‘¥¿˝÷¬À·æ…»¥≥‰◊„∂ÃªÆº¡–˚ª∑¬‰ ◊≥ﬂ≤®≥–∑€º˘∏Æ”„ÀÊøºøÃøøπª¬˙∑Ú ß∞¸◊°¥Ÿ÷¶æ÷æ˙∏À÷‹ª§—“ ¶æŸ«˙¥∫‘™≥¨∏∫…∞∑‚ªªÃ´ƒ£∆∂ºı—Ù—ÔΩ≠Œˆƒ∂ƒæ—‘«Ú≥Ø“Ω–£π≈ƒÿµæÀŒÃ˝Œ® ‰ª¨’æ¡ÌŒ¿◊÷πƒ∏’–¥¡ıŒ¢¬‘∑∂π©∞¢øÈƒ≥π¶Ã◊”—œﬁœÓ”‡µπæÌ¥¥¬…”Í»√π«‘∂∞Ô∆§≤•”≈’ºÀ¿∂æ»¶Œ∞ºæ—µøÿº§’“Ω–‘∆ª•∏˙¡—¡∏¡£ƒ∏¡∑»˚∏÷∂•≤ﬂÀ´¡ÙŒÛ¥°Œ¸◊Ëπ ¥Á∂‹ÕÌÀø≈Æ…¢∫∏π¶÷Í«◊‘∫¿‰≥πµØ¥Ì…¢…Ã ”“’√∞Ê¡“¡„ “«·—™±∂»±¿Â±√≤Ïæ¯∏ª≥«≥Â≈Á»¿ºÚ∑Ò÷˘¿ÓÕ˚≈Ã¥≈–€À∆¿ßπÆ“Ê÷ﬁÕ—Õ∂ÀÕ≈´÷–»À…œŒ™√«µÿ∏ˆ”√π§ ±“™∂Øπ˙≤˙“‘Œ“µΩÀ˚ª·◊˜¿¥∑÷…˙∂‘”⁄—ßœ¬º∂æÕƒÍΩ◊“Â∑¢≥…≤ø√Òø…≥ˆƒ‹∑ΩΩ¯Õ¨––√ÊÀµ÷÷π˝√¸∂»∂¯∂‡◊”∫Û◊‘…Áº”–°ª˙“≤æ≠¡¶œﬂ±æµÁ∏ﬂ¡ø≥§µ≥µ√ µº“∂®…Ó∑®±Ì◊≈ÀÆ¿ÌªØ’˘œ÷À˘∂˛∆’˛»˝∫√ Æ’ΩŒﬁ≈© π–‘«∞µ»∑¥ÃÂ∫œ∂∑¬∑∞—Ω·µ⁄¿Ô’˝–¬ø™¬€÷ÆŒÔ¥”µ±¡Ω–©ªπÃÏ◊  ¬∂”≈˙»Á”¶–ŒœÎ÷∆–ƒ—˘∏…∂ºœÚ±‰πÿµ„”˝÷ÿ∆‰Àº”Îº‰ƒ⁄»•“Úº˛»’¿˚œ‡”…—π‘±∆¯“µ¥˙»´◊È ˝π˚∆⁄µº∆Ω∏˜ª˘ªÚ‘¬√´»ªŒ ±»’πƒ«À¸◊Óº∞Õ‚√ªø¥÷ŒÃ·ŒÂΩ‚œµ¡÷’ﬂ√◊»∫Õ∑÷ª√˜Àƒµ¿¬Ì»œ¥ŒŒƒÕ®µ´ÃıΩœøÀ”÷π´ø◊¡Ïæ¸¡˜»ÎΩ”œØŒª«È‘À∆˜≤¢∑…‘≠”Õ≈™π“øŒ’ÚÕ˝ ¢ƒÕ‘Æ‘˙¬«º¸πÈ∑˚«Ïæ€»∆ƒ¶√¶ŒË”ˆ∑≈¡¢Ã‚÷ ÷∏Ω®«¯—ÈªÓ÷⁄∫‹ΩÃæˆÃÿ¥À≥£ Ø«øº´Õ¡“—∏˘π≤÷±Õ≈Õ≥ Ω◊™±‘Ï«–æ≈ƒ„»°Œ˜≥÷◊‹¡œ¡¨»Œ÷æπ€µ˜∆ﬂ√¥…Ω≥Ã∞Ÿ±®∏¸º˚±ÿ’Ê±£»»ŒØ ÷∏ƒπ‹¥¶º∫Ω´Õ¯Ωÿ“∞“≈æ≤ƒ±À˜πÀΩ∫—Ú∫˛∂§» “Ùº£ÀÈ…Ïµ∆±‹∑∫Õˆ¥”¬∆µπ˛Ω“∏ ≈µ∏≈œ‹≈®µ∫œÆÀ≠∫È–ª≈⁄ΩΩ∞ﬂ—∂∂Æ¡Èµ∞±’∫¢ Õ»ÈæﬁÕΩÀΩ“¯“¡æ∞Ãπ¿€‘»√π∂≈¿÷¿’∏ÙÕ‰º®’–…‹∫˙∫ÙÕ¥∑Â¡„≤Òª…Ã¯æ”…–∂°«ÿ…‘◊∑¡∫’€∫ƒºÓ ‚∏⁄Õ⁄ œ»–æÁ∂—∫’∫…–ÿ∫‚«⁄ƒ§∆™µ«◊§∞∏øØ—Ìª∫Õπ“€ºÙ¥®—©¡¥”Ê¿≤¡≥ªß¬ÂÊﬂ≤™√À¬Ú—Ó◊⁄Ωπ»¸∆Ï¬ÀπËÃøπ…◊¯’Ùƒ˝æπœ›«π¿Ëæ»√∞∞µ∂¥∑∏Õ≤ƒ˙ÀŒª°±¨√˝ÕøŒ∂ΩÚ±€’œ∫÷¬Ω∞°Ω°◊∂π∞Œƒ™µ÷∆¬æØÃÙŒ€±˘ºÌ◊Ï…∂∑πÀ‹ºƒ’‘∫∞µÊøµ◊Òƒ¡‘‚∑˘‘∞«ª∂©œ„»‚µ‹Œ›√Ùª÷Õ¸“¬ÀÔ¡‰¡Î∆≠–›ΩËµ§∂…∂˙≈Ÿª¢± œ°¿•¿À»¯≤Ë«≥”µ—®∏≤¬◊ƒÔ∂÷Ω˛–‰÷È¥∆¬Ë◊œœ∑À˛¥∏’ÀÍ√≤Ω‡∆ ¿Œ∑Ê“…∞‘…¡∆“√ÕÀﬂÀ¢∫›∫ˆ‘÷ƒ÷««Ã∆¬©Œ≈…Ú»€¬»ªƒæ•ƒ–∑≤«¿œÒΩ¨≈‘≤£“‡÷“≥™√…”Ë∑◊≤∂À¯”»≥ÀŒ⁄÷«µ≠‘ ≈—–Û∑˝√˛–‚…®±œ¡ß±¶–æ“Øº¯√ÿæªΩØ∏∆ºÁÃ⁄ø›≈◊πÏÃ√∞Ë∞÷”’◊£¿¯æ∆…˛«ÓÃ¡‘Ô≈›¥¸¿ Œπ¬¡»Ì«˛ø≈πﬂ√≥∑‡◊€«Ω«˜±ÀΩÏƒ´∞≠∆ÙƒÊ–∂∫ΩŒÌπ⁄±˚Ω÷¿≥±¥∑¯≥¶∏∂º™…¯»æ™∂Ÿº∑√Î–¸ƒ∑¿√…≠Ã« •∞ºÃ’¥ ≥Ÿ≤œ“⁄æÿ";
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
//		$randStr = array(rand(0, 9), rand(0, 9), rand(0, 9), iconv( "gb2312", "UTF-8//IGNORE" , "÷Ì"));  // ≤˙…˙4∏ˆÀÊª˙◊÷∑˚
//		$_SESSION['validate'] = $randStr[0].$randStr[1].$randStr[2].$randStr[3];
//		
//		$size = 20;
//		$width = 80;
//		$height = 25;
//		$degrees = array(rand(0, 45), rand(0, 45), rand(0, 45), rand(0, 45)); // …˙≥… ˝◊÷–˝◊™Ω«∂»
//		
//		for($i = 0; $i < 4; ++$i)
//		{
//		 if(rand() % 2);
//		 else $degrees[$i] = -$degrees[$i];
//		}
//		
//		$image = imagecreatetruecolor($size, $size);   //  ˝◊÷Õº∆¨ª≠≤º
//		$validate = imagecreatetruecolor($width, $height);  // ◊Ó÷’—È÷§¬Îª≠≤º
//		$back = imagecolorallocate($image, 255, 255, 255);  // ±≥æ∞…´
//		$border = imagecolorallocate($image, 0, 0, 0);    // ±ﬂøÚ
//		imagefilledrectangle($validate, 0, 0, $width, $height, $back); // ª≠≥ˆ±≥æ∞…´
//		
//		//  ˝◊÷—’…´
//		for($i = 0; $i < 4; ++$i)
//		{
//		 // øº¬«Œ™ π◊÷∑˚»›“◊ø¥«Â π”√—’…´Ωœ∞µµƒ—’…´
//		 $temp = $this->RgbToHsv(rand(0, 255), rand(0, 255), rand(0, 255));
//		 
//		 if($temp[2] > 60)
//		  $temp [2] = 60;
//		
//		 $temp = $this->HsvToRgb($temp[0], $temp[1], $temp[2]);
//		 $textcolor[$i] = imagecolorallocate($image, $temp[0], $temp[1], $temp[2]);
//		}
//		
//		for($i = 0; $i < 200; ++$i) //º”»Î∏…»≈œÛÀÿ
//		{
//		 $randpixelcolor = ImageColorallocate($validate, rand(0, 255), rand(0, 255), rand(0, 255));
//		 imagesetpixel($validate, rand(1, 87), rand(1, 27), $randpixelcolor);
//		}
//		
//		// ∏…»≈œﬂ π”√—’…´Ωœ√˜¡¡µƒ—’…´
//		$temp = $this->RgbToHsv(rand(0, 255), rand(0, 255), rand(0, 255));
//		
//		if($temp[2] < 200)
//		 $temp [2] = 255;
//		 
//		$temp = $this->HsvToRgb($temp[0], $temp[1], $temp[2]);
//		$randlinecolor = imagecolorallocate($image, $temp[0], $temp[1], $temp[2]);
//		
//		// ª≠5Ãı∏…»≈œﬂ
//		for ($i = 0;$i < 5; $i ++)
//		 imageline($validate, rand(1, 79), rand(1, 24), rand(1, 79), rand(1, 24), $randpixelcolor);
//		
//		imagefilledrectangle($image, 0, 0, $size, $size, $back); // ª≠≥ˆ±≥æ∞…´ 
//		imagestring($image, 5, 6, 2, $randStr[0], $textcolor[0]);  // ª≠≥ˆ ˝◊÷
//		$image = imagerotate($image, $degrees[0], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 1, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		$image = imagecreatetruecolor($size, $size); // À¢–¬ª≠∞Â
//		imagefilledrectangle($image, 0, 0, $size, $size, $back);  // ª≠≥ˆ±≥æ∞…´ 
//		imagestring($image, 5, 6, 2, $randStr[1], $textcolor[1]);  // ª≠≥ˆ ˝◊÷
//		$image = imagerotate($image, $degrees[1], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 21, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		$image = imagecreatetruecolor($size, $size); // À¢–¬ª≠∞Â
//		imagefilledrectangle($image, 0, 0, $size - 1, $size - 1, $back);  // ª≠≥ˆ±≥æ∞…´ 
//		imagestring($image, 5, 6, 2, $randStr[2], $textcolor[2]);  // ª≠≥ˆ ˝◊÷
//		$image = imagerotate($image, $degrees[2], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 41, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		$image = imagecreatetruecolor($size, $size); // À¢–¬ª≠∞Â
//		imagefilledrectangle($image, 0, 0, $size - 1, $size - 1, $back);  // ª≠≥ˆ±≥æ∞…´ 
//		imagestring($image, 5, 6, 2, $randStr[3], $textcolor[3]);  // ª≠≥ˆ ˝◊÷
//		$image = imagerotate($image, $degrees[3], $back);
//		imagecolortransparent($image, $back);
//		imagecopymerge($validate, $image, 61, 4, 4, 5, imagesx($image) - 10, imagesy($image) - 10, 100);
//		
//		imagerectangle($validate, 0, 0, $width - 1, $height - 1, $border);  // ª≠≥ˆ±ﬂøÚ
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
