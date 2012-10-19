<?php
/**
 *        UBB类
 *        version: 1.0.0
 *  author: elliott [at] 2004-08-30
 *  增强广泛支持: 2004-12-9 2004-11-25
 */
class Lib_Ubb
{
        var $str;
        var $pattern = array();    //存放UBB标签的字符数组
        var $replace = array();    //存放代转换的字符数组

        var $parseTable_ = true;                //分析表格类格式
        var $parseFont_ = true;                //分析字体类格式
        var $parseLink_ = true;                //分析链接类格式
        var $parseCode_ = true;                //分析代码类格式
        var $parseMedia_ = true;                //分析媒体类格式

        /**
         *        设置待分析的字符串
         *        @param $str 待分析的字符串
         *        @return null
         */
        function setString($str)
        {
                $this->str = $str;
        }

        /**
         *        设置是否分析字体格式
         *        @param $tag 为 true 时分析字体格式,为 false 时不分析
         *        @return null
         */
        function setParseFont($tag)
        {
                $this->parseFont_ = $tag;
        }
#################################
        /**
         *        设置是否分析表格格式
         *        @param $tag 为 true 时分析字体格式,为 false 时不分析
         *        @return null
         */
        function setparseTable($tag)
        {
                $this->parseTable_ = $tag;
        }

#################################

        /**
         *        设置是否分析链接类格式
         *        @param $tag 为 true 时分析链接类格式,为 false 时不分析
         *        @return null
         */
        function setParseLink($tag)
        {
                $this->parseLink_ = $tag;
        }

        /**
         *        设置是否分析代码类格式
         *        @param $tag 为 true 时分析代码类格式,为 false 时不分析
         *        @return null
         */
        function setParseCode($tag)
        {
                $this->parseCode_ = $tag;
        }

        /**
         *        设置是否分析媒体类格式
         *        @param $tag 为 true 时分析媒体类格式,为 false 时不分析
         *        @return null
         */
        function setParseMedia($tag)
        {
                $this->parseMedia_ = $tag;
        }

        /**
         *        设置分析本类支持的全部UBB格式
         *        @param null
         *        @return null
         */
        function setParseAll()
        {
                $this->$parseTable_ = true;
                $this->parseFont_ = true;
                $this->parseLink_ = true;                $this->parseCode_ = true;
                $this->parseMedia_ = true;
        }
##########################
        /**
         *        分析表格类标签.
         *        @param null
         *        @return null
         */
        function parseTable()
        {
                $pattern = array(
                                             '/\[table\]\s*(.+?)\[\/table\]/is',
                                             '/\[tr\]\s*(.+?)\[\/tr\]/is',
                                             '/\[td\]\s*(.+?)\[\/td\]/is',
                                             '/\[th\]\s*(.+?)\[\/th\]/is',
                                                 '/\[tdrow=\s*(.+?)\]\s*(.+?)\[\/td\]/is',
                                                 '/\[tdcol=\s*(.+?)\]\s*(.+?)\[\/td]/is',
                                                 '/\[throw=\s*(.+?)\]\s*(.+?)\[\/th\]/is',
                                                 '/\[thcol=\s*(.+?)\]\s*(.+?)\[\/th]/is',
                                             '/\[list\]\s*(.+?)\[\/list\]/is',
                                             '/\[\*\]\s*([^\[]*)/is',
                                             '/\[list\]\s*(.+?)\[\/list:u\]/is',
                                                 '/\[list=1\]\s*(.+?)\[\/list\]/is',
                                                 '/\[list=i\]\s*(.+?)\[\/list\]/is',
                                                 '/\[list=I\]\s*(.+?)\[\/list\]/is',
                                                 '/\[list=a\]\s*(.+?)\[\/list\]/is',
                                                 '/\[list=A\]\s*(.+?)\[\/list\]/is',
                                             '/\[list\]\s*(.+?)\[\/list:o\]/is',
                                    );
                $this->pattern = array_merge($this->pattern, $pattern);

                $replace = array(
                                             '<table border="1">\\1</table>',
                                             '<tr>\\1</tr>',
                                             '<td>\\1</td>',
                                             '<th>\\1</th>',
                                                 '<td rowspan="\\1">\\2</td>',
                                             '<td colspan=\\1>\\2</td>',
                                                 '<th rowspan="\\1">\\2</th>',
                                             '<th colspan=\\1>\\2</th>',
                                             '<ul>\\1</ul>',
                                             '<li>\\1</li>',
                                             '<ul>\\1</ul>',
                                                '<ol style="list-style-type:decimal;">\\1</ol>',
                                                '<ol style="list-style-type:lower-roman;">\\1</ol>',
                                                '<ol style="list-style-type:upper-roman;">\\1</ol>',
                                                '<ol style="list-style-type:lower-alpha;">\\1</ol>',
                                                '<ol style="list-style-type:upper-alpha;">\\1</ol>',
                                                '<ol style="list-style-type:decimal;">\\1</ol>',
                                );
                $this->replace = array_merge($this->replace, $replace);
        }
##########################
        /**
         *        分析字体类标签,如字体大小,颜色,移动等.
         *        @param null
         *        @return null
         */
        function parseFont()
        {
                $pattern = array(
                                             '/\[h1\]\s*(.+?)\[\/h1\]/is',
                                             '/\[h2\]\s*(.+?)\[\/h2\]/is',
                                             '/\[h3\]\s*(.+?)\[\/h3\]/is',
                                             '/\[h4\]\s*(.+?)\[\/h4\]/is',
                                             '/\[h5\]\s*(.+?)\[\/h5\]/is',
                                             '/\[h6\]\s*(.+?)\[\/h6\]/is',
                                             '/\[b\]\s*(.+?)\[\/b\]/is',
                                             '/\[u\]\s*(.+?)\[\/u\]/is',
                                             '/\[i\]\s*(.+?)\[\/i\]/is',
                                             '/\[s\]\s*(.+?)\[\/s\]/is',
                                             '/\[strike\]\s*(.+?)\[\/strike\]/is',
                                                 '/\[size=\s*(.+?)\]\s*(.+?)\[\/size\]/is',
                                                 '/\[color=\s*(.+?)\]\s*(.+?)\[\/color]/is',
                                                 '/\[font=\s*(.+?)\]\s*(.+?)\[\/font\]/is',
                                             '/\[fly\]\s*(.+?)\[\/fly\]/is',
                                             '/\[move\]\s*(.+?)\[\/move\]/is',
                                                 '/\[sub\]\s*(.+?)\[\/sub\]/is',
                                                 '/\[sup\]\s*(.+?)\[\/sup\]/is',
                                                 '/\[left\]\s*(.+?)\[\/left\]/is',
                                                 '/\[center\]\s*(.+?)\[\/center\]/is',
                                                 '/\[right\]\s*(.+?)\[\/right\]/is',
                                                 '/\[justify\]\s*(.+?)\[\/justify\]/is',
                                             '/\[shadow=(\S+?)\s*\](.*?)\[\/shadow\]/is',
                                                 '/\[glow=(\S+?)\s*\](.*?)\[\/glow\]/is',
                                                 '/\[fliph\](.+?)\[\/fliph\]/is',
                                                 '/\[flipv\](.+?)\[\/flipv\]/is',
                                                 '/\[blur\](.*?)\[\/blur\]/is',
                                                 '/\[align\s*=\s*(\S+?)\s*\](.*?)\[\/align\]/is',
                                                 '/\[dropshadow=(\S+?)\s*\](.*?)\[\/dropshadow\]/is',
                                                 '/\[invert\](.*?)\[\/invert\]/is',
                                                 '/\[xray\](.*?)\[\/xray\]/is',
                                                 '/\[spoiler\](.*)\[\/spoiler\]/is',
                                    );
                $this->pattern = array_merge($this->pattern, $pattern);

                $replace = array(
                                             '<h1>\\1</h1>',
                                             '<h2>\\1</h2>',
                                             '<h3>\\1</h3>',
                                             '<h4>\\1</h4>',
                                             '<h5>\\1</h5>',
                                             '<h6>\\1</h6>',
                                             '<strong>\\1</strong>',
                                             '<u>\\1</u>',
                                             '<em>\\1</em>',
                                             '<s>\\1</s>',
                                             '<strike>\\1</strike>',
                                                 '<span style="font-size:\\1">\\2</span>',
                                             '<span style="color:\\1">\\2</span>',
                                                 '<span style="font-family:\\1">\\2</span>',
                                                 '<MARQUEE>\\1</MARQUEE>',
                                                 '<MARQUEE>\\1</MARQUEE>',
                                                 '<sub>\\1</sub>',
                                                 '<sup>\\1</sup>',
                                                 '<div align="left">\\1</div>',
                                                 '<div align="center">\\1</div>',
                                                 '<div align="right">\\1</div>',
                                                 '<div align="justify">\\1</div>',
                                                 '<font style="width=80%; filter:shadow\(color=\\1)">\\2</font>',
                                                 '<font style="width=80%; filter:glow\(color=\\1)">\\2</font>',
                                                 '<font style="width=80%; filter:flipH">\\1</font>',
                                                 '<font style="width=80%; filter:flipV">\\1</font>',
                                                 '<font style="width=80%; filter:blur">\\1</font>',
                                                 '<div align="\\1">\\2</div>',
                                                 '<font style="width=80%; filter:dropshadow(color=\\1)">\\2</font>',
                                                 '<font style="width=80%; filter:invert">\\2</font>',
                                                 '<font style="width=80%; filter:xray">\\2</font>',
                                                 '<table border="0" cellpadding="0" cellspacing="0"><tr><td bgcolor="#000000" valign="middle" align="left"><font color="#FF0000" size="1"><b>Spoiler效果文字: </b></font><br /></td></tr><tr><td bgcolor="#000000" valign="middle" align="left"><font color="#00FF00" size="1">\\1</td></tr></table>',
                                );
                $this->replace = array_merge($this->replace, $replace);
        }

        /**
         *        分析代码类标签,如引用,例子,iframe等.
         *        @param null
         *        @return null
         */
        function parseCode()
        {
                $pattern = array(
                                                '/\[p\]/is',
                                                '/\[\/p\]/is',
                                                 '/\[hr\]/is',
                                             '/\[pre\]\s*(.+?)\[\/pre\]/is',
                                             '/\[quote\]\s*(.+?)\[\/quote\]/is',
                                             '/\[code\]\s*(.+?)\[\/code\]/is',
                                             '/\[iframe\]\s*(.+?)\[\/iframe\]/is',
                                             '/\[sig\](.+?)\[\/sig\]/is',
                                             '/\[reply\]\s*(.+?)\[\/reply\]/is',
                                    );
                $this->pattern = array_merge($this->pattern, $pattern);

                $replace = array(
                                                 '<p>',
                                                 '</p>',
                                                 '<hr size="1" />',
                                                 '<pre>\\1</pre>',
                                                 '<p>&nbsp;&nbsp;引用:<hr size="1">\\1<hr size="1"></p>',
                                                 '<p><table border="0" width="97%" cellspacing="0" cellpadding="0"><tr><td>&nbsp;&nbsp;代码:</td></tr><tr><td><table border="0" width="100%" cellspacing="1" cellpadding="10" bgcolor="#000000"><tr><td align="left" width="100%" bgcolor="#e6e6e6"><font face="Courier New">\\1</font></td></tr></table></td></tr></table></p>',
                                                 '<iframe src="\\1" frameborder="0" allowtransparency="true" scrolling="yes" width="500" height="480">对不起，您的浏览器不支持IFRAME，<a href="\\1" target="_blank">点击这里打开该页面</a>。</iframe>',
                                                 '<p><div style="text-align: left; color: darkgreen; margin-left: 5%"><br /><br />--------------------------<br />\\1<br />--------------------------</div></p>',
                                                 '<hr size="0" noshade width="95%"><span style="color: red; padding: 0px 0px 0px 5em; width: 90%;">\\1</span>',
                                    );
                $this->replace = array_merge($this->replace, $replace);
        }

        /**
         *        分析连接类标签,如URL,EMAIL,图像等
         *        @param null
         *        @return null
         *        应该判断是否是图片以 http|https|ftp 或 / 开头（好象也不用。）(javascript)|(jscript)|(vbscript)|(.js)
         */
        function parseLink()
        {
                $pattern = array(
                                             '/\[url\]\s*(.+?)\[\/url\]/is',
                                                 '/\[url=\s*(.+?)\]\s*(.+?)\[\/url\]/is',
                                                '/\[url\]www\.\s*(.+?)\[\/url\]/is',
                                             '/\[ed\]\s*(.+?)\[\/ed\]/is',
                                             '/\[email\]\s*(.+?)\[\/email\]/is',
                                                 '/\[email=\s*(.+?)\]\s*(.+?)\[\/email\]/is',
                                                 '/\[img\]\s*(.+?)\[\/img\]/is',
                                                 '/\[limg\]\s*(.+?)\[\/limg\]/is',
                                                 '/\[cimg\]\s*(.+?)\[\/cimg\]/is',
                                                 '/\[rimg\]\s*(.+?)\[\/rimg\]/is',
                                                 '/\[img=(\d+),\s*(\d+)\]\s*(.+?)\[\/img\]/is',
                                    );
                $this->pattern = array_merge($this->pattern, $pattern);

                $replace = array(
                                                 '<a href="\\1" target="_blank">\\1</a>',
                                                 '<a href="\\1" target="_blank">\\2</a>',
                                                 '<a href="http://www.\\1" target="_blank">\\1</a>',
                                                 '<a href="\\1" target="_blank"><b>\\2</b></a>',
                                                 '<a href="mailto:\\1">\\1</a>',
                                                 '<a href="mailto:\\1">\\2</a>',
                                                 '<img src="\\1" border="0" onload="if(this.width>500) this.width=500;this.title=\'看原大图\';" onmouseover="if(this.title) this.style.cursor=\'hand\';"; onclick="if(this.title) window.open(\'\\1\');">',
                                                 '<img src="\\1" align="left" border="0" onload="if(this.width>500) this.width=500;this.title=\'看原大图\';" onmouseover="if(this.title) this.style.cursor=\'hand\';"; onclick="if(this.title) window.open(\'\\1\');">',
                                                 '<div align="center"><img src="\\1" border="0" onload="if(this.width>500) this.width=500;this.title=\'看原大图\';" onmouseover="if(this.title) this.style.cursor=\'hand\';"; onclick="if(this.title) window.open(\'\\1\');"></div>',
                                                 '<img src="\\1" align="right" border="0" onload="if(this.width>500) this.width=500;this.title=\'看原大图\';" onmouseover="if(this.title) this.style.cursor=\'hand\';"; onclick="if(this.title) window.open(\'\\1\');">',
                                                 '<img src="\\3" border="0" height="\\2" width="\\1" onclick="if(this.title) window.open(\'\\3\');">',
                                                );
                $this->replace = array_merge($this->replace, $replace);
        }

        /**
         *        分析媒体类标签,如flash,rm,wmv,mp3...
         *        @param null
         *        @return null
         */
        function parseMedia()
        {
                $pattern = array(
                                                 '/\[swf\]\s*(.+?)\[\/swf\]/is',
                                                 '/\[swf=(\d+),\s*(\d+)\]\s*(.+?)\[\/swf\]/is',
                                                 '/\[flash\]\s*(.+?)\[\/flash\]/is',
                                                 '/\[flash=(\d+),\s*(\d+)\]\s*(.+?)\[\/flash\]/is',
                                                 '/\[wma\]\s*(.+?)\[\/wma\]/is',
                                                 '/\[mp3\]\s*(.+?)\[\/mp3\]/is',
                                                 '/\[wmv\]\s*(.+?)\[\/wmv\]/is',
                                                 '/\[ra\]\s*(.+?)\[\/ra\]/is',
                                                 '/\[rm\]\s*(.+?)\[\/rm\]/is',
                                                 '/\[rm=(\d+),\s*(\d+)\]\s*(.+?)\[\/rm\]/is',
                                                 '/\[media\](.+?)\.((rm)|(ra)|(ram)|(rpm)|(wmv)|(asf))\[\/media\]/is',
                                                 '/\[media=(.+?)\](.+?)\.((rm)|(ra)|(ram)|(rpm)|(wmv)|(asf))\[\/media\]/is',
                                                 '/\[media\](.+?)\.((mid)|(wav)|(mp3))\[\/media\]/is',
                                                 '/\[media\](.+?)\[\/media\]/is',
                                                 '/\[wmv=(.+?)\](.+?)\[\/wmv\]/is',
                                                 '/\[mov\](.+?)\[\/mov\]/is',
                                                 '/\[music\](.+?)\[\/music\]/is',
                                                 '/\[music=(.+?)\](.+?)\[\/music\]/is',
                                                 '/\[dir=(\d+),\s*(\d+)\]\s*(.+?)\[\/dir\]/is',
                                                 '/\[mp=(\d+),\s*(\d+):\s*(.+?)\]\s*(.+?)\[\/mp\]/is',
                                                 '/\[qt=(\d+),\s*(\d+):\s*(.+?)\]\s*(.+?)\[\/qt\]/is',
                                                 '/\[rm=(\d+),\s*(\d+):\s*(.+?)\]\s*(.+?)\[\/rm\]/is',
                                    );
                $this->pattern = array_merge($this->pattern, $pattern);

                $replace = array(
                                                '<embed src="\\1" type="application/x-shockwave-flash" PLAY="1"></embed>',
                                                '<embed width="\\1" height="\\2" src="\\3" type="application/x-shockwave-flash" PLAY="1"></embed>',
                                                '<embed src="\\1" type="application/x-shockwave-flash" PLAY="1"></embed>',
                                                '<embed width="\\1" height="\\2" src="\\3" type="application/x-shockwave-flash" PLAY="1"></embed>',
                                                '<embed src="\\1"  height="45" width="314" autostart="0"></embed>',
                                                '<embed src="\\1"  height="45" width="314" autostart="0"></embed>',
                                                '<embed src="\\1"  height="256" width="314" autostart="0"></embed>',
                                                '<object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" id="RAOCX" width="253" height="60">
       <param name="_ExtentX" value="6694">
       <param name="_ExtentY" value="1588">
       <param name="AUTOSTART" value="0">
       <param name="SHUFFLE" value="0">
       <param name="PREFETCH" value="0">
       <param name="NOLABELS" value="0">
       <param name="SRC" value="\\1">
       <param name="CONTROLS" value="StatusBar,ControlPanel">
       <param name="LOOP" value="0">
       <param name="NUMLOOP" value="0">
       <param name="CENTER" value="0">
       <param name="MAINTAINASPECT" value="0">
       <param name="BACKGROUNDCOLOR" value="#000000">
       <embed src="\\1" width="253" autostart="true" height="60">
       </embed></object>',
                                                '<object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height="241" id="Player" width="316" VIEWASTEXT>
       <param name="_ExtentX" value="12726">
       <param name="_ExtentY" value="8520">
       <param name="AUTOSTART" value="0">
       <param name="SHUFFLE" value="0">
       <param name="PREFETCH" value="0">
       <param name="NOLABELS" value="0">
       <param name="CONTROLS" value="ImageWindow">
       <param name="CONSOLE" value="_master">
       <param name="LOOP" value="0">
       <param name="NUMLOOP" value="0">
       <param name="CENTER" value="0">
       <param name="MAINTAINASPECT" value="\\1">
       <param name="BACKGROUNDCOLOR" value="#000000">
     </object><br />
     <object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height="32" id="Player" width="316" VIEWASTEXT VIEWASTEXT>
       <param name="_ExtentX" value="18256">
       <param name="_ExtentY" value="794">
       <param name="AUTOSTART" value="0">
       <param name="SHUFFLE" value="0">
       <param name="PREFETCH" value="0">
       <param name="NOLABELS" value="0">
       <param name="CONTROLS" value="controlpanel">
       <param name="CONSOLE" value="_master">
       <param name="LOOP" value="0">
       <param name="NUMLOOP" value="0">
       <param name="CENTER" value="0">
       <param name="MAINTAINASPECT" value="0">
       <param name="BACKGROUNDCOLOR" value="#000000">
       <param name="SRC" value="\\1">
     </object>',
                                                '<object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height="\\2" id="Player" width="\\1" VIEWASTEXT>
       <param name="_ExtentX" value="12726">
       <param name="_ExtentY" value="8520">
       <param name="AUTOSTART" value="0">
       <param name="SHUFFLE" value="0">
       <param name="PREFETCH" value="0">
       <param name="NOLABELS" value="0">
       <param name="CONTROLS" value="ImageWindow">
       <param name="CONSOLE" value="_master">
       <param name="LOOP" value="0">
       <param name="NUMLOOP" value="0">
       <param name="CENTER" value="0">
       <param name="MAINTAINASPECT" value="\\3">
       <param name="BACKGROUNDCOLOR" value="#000000">
     </object><br />
     <object classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA height="32" id="Player" width="\\1" VIEWASTEXT VIEWASTEXT>
       <param name="_ExtentX" value="18256">
       <param name="_ExtentY" value="794">
       <param name="AUTOSTART" value="0">
       <param name="SHUFFLE" value="0">
       <param name="PREFETCH" value="0">
       <param name="NOLABELS" value="0">
       <param name="CONTROLS" value="controlpanel">
       <param name="CONSOLE" value="_master">
       <param name="LOOP" value="0">
       <param name="NUMLOOP" value="0">
       <param name="CENTER" value="0">
       <param name="MAINTAINASPECT" value="0">
       <param name="BACKGROUNDCOLOR" value="#000000">
       <param name="SRC" value="\\3">
     </object>',
                                                '<OBJECT ID=realwindow CLASSID="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" HEIGHT=240 WIDTH=352><PARAM NAME="controls" VALUE="ImageWindow"><PARAM NAME="console" VALUE="MediaClip1"><PARAM NAME="autostart" VALUE="false"><PARAM NAME="src"  VALUE="\\1.\\2"><EMBED SRC="\\1.\\2" type="audio/x-pn-realaudio-plugin"  CONSOLE="MediaClip1" CONTROLS="ImageWindow" HEIGHT=240 WIDTH=352 AUTOSTART=false></embed></OBJECT><br /><OBJECT ID=realcontrol CLASSID="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" HEIGHT=60 WIDTH=352><PARAM NAME="controls" VALUE="ControlPanel,StatusBar"><PARAM NAME="console" VALUE="MediaClip1"><EMBED type="audio/x-pn-realaudio-plugin" CONSOLE="MediaClip1" CONTROLS="ControlPanel,StatusBar" HEIGHT=60 WIDTH=352 AUTOSTART=false></embed></OBJECT><br /><a href="\\1.\\2" target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<OBJECT ID=realwindow CLASSID="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" HEIGHT=240 WIDTH=352><PARAM NAME="controls" VALUE="ImageWindow"><PARAM NAME="console" VALUE="MediaClip\\1"><PARAM NAME="autostart" VALUE="false"><PARAM NAME="src"  VALUE="\\2.\\3"><EMBED SRC="\\2.\\3" type="audio/x-pn-realaudio-plugin"  CONSOLE="MediaClip\\1" CONTROLS="ImageWindow" HEIGHT=240 WIDTH=352 AUTOSTART=false></embed></OBJECT><br /><OBJECT ID=realcontrol CLASSID="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" HEIGHT=60 WIDTH=352><PARAM NAME="controls" VALUE="ControlPanel,StatusBar"><PARAM NAME="console" VALUE="MediaClip\\1"><EMBED type="audio/x-pn-realaudio-plugin" CONSOLE="MediaClip\\1" CONTROLS="ControlPanel,StatusBar" HEIGHT=60 WIDTH=352 AUTOSTART=false></embed></OBJECT><br /><a href="\\2.\\3" target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<OBJECT WIDTH=320 HEIGHT=70 CLASSID="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95"><PARAM NAME="ShowStatusBar" value="-1"><PARAM NAME="AutoStart" VALUE="0"><PARAM NAME="FileName" VALUE="\\1.\\2"></OBJECT><br /><a href="\\1.\\2" target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<OBJECT WIDTH=320 HEIGHT=300 CLASSID="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95"><PARAM NAME="ShowStatusBar" value="-1"><PARAM NAME="AutoStart" VALUE="0"><PARAM NAME="FileName" VALUE="\\1"></OBJECT><br /><a href="\\1" target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<embed SRC="\\2" type="audio/x-ms-wmp" CONSOLE="WMVClip\\1" CONTROLS="ImageWindow,ControlPanel,StatusBar" HEIGHT="240" WIDTH="352" AUTOSTART="false"></embed><br /><a href="\\2" target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<embed src=\\1 TYPE="image/x-quicktime" width="320" height="256" autostart="false" videocontrols="on" pluginspage="http://www.apple.com/quicktime/download/"></embed><br /><a href=\\1 target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<param name="BACKGROUNDCOLOR" <br /><embed src="\\1" align="baseline" border="0" width="275" height="40" type="audio/x-pn-realaudio-plugin" console="MusicClip1" controls="ControlPanel" autostart="false"><br /><a href="\\1" target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<param name="BACKGROUNDCOLOR" <br /><embed src="\\2" align="baseline" border="0" width="275" height="40" type="audio/x-pn-realaudio-plugin" console="MusicClip\\1" controls="ControlPanel" autostart="false"><br /><a href="\\2" target="_blank">--- 右击鼠标，然后保存 ---</a>',
                                                '<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=7,0,2,0" width="\\1" height="\\2">
                                                        <param name="src" value="\\3">
                                                        <embed src="\\3" pluginspage="http://www.macromedia.com/shockwave/download/" width="\\1" height="\\2">
                                                        </embed></object>',
                                                '<object align="middle" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" id="MediaPlayer" width="\\1" height="\\2" >
                                                        <param name="ShowStatusBar" value="-1">
                                                        <param name="Filename" value="\\4"><param name="AutoStart" value="0">
                                                        <embed type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" flename="mp" src="\\4" width="\\1" height="\\2">
                                                        </embed></object>',
                                                '<embed src="\\4" width="\\1" height="\\2" autoplay="0" loop="false" controller="true" playeveryframe="false" cache="false" scale="TOFIT" bgcolor="#000000" kioskmode="false" targetcache="false" pluginspage="http://www.apple.com/quicktime/">',
                                                '<OBJECT classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" class="OBJECT" id="RAOCX" width="\\1" height="\\2">
                                                        <PARAM NAME="SRC" value="\\4">
                                                        <PARAM NAME="CONSOLE" VALUE="20041290462273950">
                                                        <PARAM NAME="CONTROLS" VALUE="imagewindow">
                                                        <PARAM NAME="AUTOSTART" VALUE="0">
                                                        </OBJECT><br>
                                                        <OBJECT classid="CLSID:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" height="32" id="video2" width="\\1">
                                                        <PARAM NAME=SRC value="\\4">
                                                        <PARAM NAME="AUTOSTART" VALUE="0">
                                                        <PARAM NAME="CONTROLS" VALUE="controlpanel">
                                                        <PARAM NAME="CONSOLE" VALUE="20041290462273950"></OBJECT>',
                                                );
                $this->replace = array_merge($this->replace, $replace);
        }

function autoaddlink() {
  //----- 自动转换相似地址为链接 -----------
    $urlSearchArray = array(
      "/([^]_a-z0-9-=\"'\/])((https?|ftp|mms|gopher|rtsp|pnm|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*)/si",
      "/^((https?|ftp|mms|gopher|rtsp|pnm|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si"
    );
    $urlReplaceArray = array(
     "\\1<a href=\"\\2\\4\" target=\"_blank\">\\2\\4</a>",
    "<a href=\"\\1\\3\" target=\"_blank\">\\1\\3</a>"
   );
                $this->pattern = array_merge($this->pattern, $urlSearchArray);
                $this->replace = array_merge($this->replace, $urlReplaceArray);
}

        /**
         *        分析UBB类格式
         *        @param null
         *        @return $str 分析完后的UBB格式
         */
        function parse()
        {
                //print_r($this->pattern);
                //print_r($this->replace);

                //echo $this->str;

                if($this->parseTable_)
                        $this->parseTable();
                if($this->parseFont_)
                        $this->parseFont();
                if($this->parseLink_)
                        $this->parseLink();
                if($this->parseCode_)
                        $this->parseCode();
                if($this->parseMedia_)
                        $this->parseMedia();
                $autoaddlink=0;                //改为 1 自动转换相似地址为链接
                if($autoaddlink==1)
                        $this->autoaddlink();

                $this->str = str_replace("\n", "<br />", $this->str);
                $this->str = preg_replace($this->pattern, $this->replace, $this->str);
                return $this->str;
        }
}
        /**
         *        清除UBB代码
         *        @param null
         *        @return $str 分析完后的UBB格式
         */
function clearUbb($Text)
{
   $Text=str_replace("\n","",$Text);
   $Text=str_replace("  ","",$Text);
   $Text=str_replace(":ex_1:","",$Text);
   $Text=str_replace(":ex_2:","",$Text);
   $Text=str_replace(":ex_3:","",$Text);
   $Text=str_replace(":ex_4:","",$Text);
   $Text=str_replace(":ex_5:","",$Text);
   $Text=str_replace(":ex_6:","",$Text);
   $Text=str_replace(":ex_7:","",$Text);
   $Text=str_replace(":ex_8:","",$Text);
   $Text=str_replace(":ex_9:","",$Text);
   $Text=str_replace(":ex_10:","",$Text);
   $Text=str_replace(":ex_11:","",$Text);
   $Text=str_replace(":ex_12:","",$Text);
   $Text=str_replace(":ex_13:","",$Text);
   $Text=str_replace(":ex_14:","",$Text);
   $Text=str_replace(":ex_15:","",$Text);
   $Text=str_replace(":ex_16:","",$Text);
   $Text=str_replace(":ex_17:","",$Text);
   $Text=str_replace(":ex_18:","",$Text);
   $Text=str_replace(":ex_19:","",$Text);
   $Text=str_replace(":ex_20:","",$Text);
   $Text=str_replace(":ex_21:","",$Text);
   $Text=str_replace(":ex_22:","",$Text);
   $Text=str_replace(":ex_23:","",$Text);
   $Text=str_replace(":ex_24:","",$Text);
   $Text=str_replace(":ex_25:","",$Text);
   $Text=str_replace(":ex_26:","",$Text);
   $Text=str_replace(":ex_27:","",$Text);
   $Text=str_replace(":ex_28:","",$Text);
   $Text=str_replace(":ex_29:","",$Text);
   $Text=str_replace(":ex_30:","",$Text);
   $Text=str_replace(":ex_31:","",$Text);
   $Text=str_replace(":ex_32:","",$Text);
   $Text=str_replace(":ex_33:","",$Text);
   $Text=str_replace(":ex_34:","",$Text);
   $Text=str_replace(":ex_35:","",$Text);
   $Text=str_replace(":ex_36:","",$Text);
   $Text=str_replace(":ex_37:","",$Text);
   $Text=str_replace(":ex_38:","",$Text);
   $Text=ereg_replace("\r\n","",$Text);
   $Text=ereg_replace("\r","",$Text);
   $Text=preg_replace("/\\t/is","  ",$Text);
   $Text=preg_replace("/\[color=(.+?)\](.*)\[\/color\]/is","\\2",$Text);
   $Text=preg_replace("/\[color=\s*(.*?)\s*\]\s*(.*?)\s*\[\/color\]/is","\\2",$Text);
   $Text=preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is","",$Text);
   $Text=preg_replace("/\[url\](.+?)\[\/url\]/is","",$Text);
   $Text=preg_replace("/\[url=(http:\/\/.+?)\](.*)\[\/url\]/is","",$Text);
   $Text=preg_replace("/\[url=(.+?)\](.*)\[\/url\]/is","",$Text);
   $Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","\\1",$Text);
   $Text=preg_replace("/\[email\](.+?)\[\/email\]/is","\\1",$Text);
   $Text=preg_replace("/\[i\](.+?)\[\/i\]/is","\\1",$Text);
   $Text=preg_replace("/\[u\](.+?)\[\/u\]/is","\\1",$Text);
   $Text=preg_replace("/\[b\](.+?)\[\/b\]/is","\\1",$Text);
   $Text=preg_replace("/\[img\](.+?)\[\/img\]/is","",$Text);
   $Text=preg_replace("/\[rimg\](.+?)\[\/img\]/is","",$Text);
   $Text=preg_replace("/\[limg\](.+?)\[\/img\]/is","",$Text);
   $Text=preg_replace("/\[iframe\]\s*(.+?)\s*\[\/iframe\]/is","",$Text);
   $Text=preg_replace("/\[swf\]\s*(.+?)\s*\[\/swf\]/is","",$Text);
   $Text=preg_replace("/\[wmv\]\s*(.+?)\s*\[\/wmv\]/is","",$Text);
   $Text=preg_replace("/\[wma\]\s*(.+?)\s*\[\/wma\]/is","",$Text);
   $Text=preg_replace("/\[ra\]\s*(.+?)\s*\[\/ra\]/is","",$Text);
   $Text=preg_replace("/\[rm\]\s*(.+?)\s*\[\/rm\]/is","",$Text);
   return $Text;
}

//example:


/*$str = "[url=http://blog.chinaunix.net/u/19869/showart_257060.html][color=DarkRed]phpStudy----PHP5开发环境集成包 [/color][/url][b]phpshao[/b]";
$Ubb = new Ubb();
$Ubb->setString($str);
$str2 = $Ubb->parse();
echo $str2;*/


?>

