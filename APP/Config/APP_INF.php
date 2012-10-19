<?php

/**
 * 定义应用程序设置 
*/

return array(
    /**
     * 应用程序标题
     */
    //'appTitle' => 'FleaPHP Example SHOP',

    /**
     * 指定默认控制器
     */
    //'defaultController' => 'Login',
	'defaultController' => 'ModaIndex',

	//'defaultController' => 'User',
	//'defaultAction' => 'EnterIndexpage',

    /**
     * 指定要使用的调度器
     */
	 
     'dispatcher' => 'FLEA_Dispatcher_Auth',
     /**
     * 指示 RBAC 组件用什么键名在 session 中保存用户数据
     */
	'RBACSessionKey' => 'ktvRBAC',
	 
    /**
     * 指示应用程序内部处理数据及页面显示要使用的编码
     */
    'responseCharset' => 'gbk',

    /**
     * 指示数据库要使用的编码
     */
    'databaseCharset' => 'gbk',

    /**
     * 启用多语言支持
     */
    //'multiLangaugeSupport' => true,

    /**
     * 指定语言文件所在目录
     */
  	 // 'languageFilesDir' => realpath(dirname(__FILE__) . '/../Languages'),

    /**
     * 指示可用的语言
     */
 	/*'languages' => array(
        '简体中文' => 'chinese-utf8',
        '繁体中文' => 'chinese-utf8-tw',
    ),*/

    /**
     * 指示默认语言
     */
    //'defaultLanguage' => 'chinese-utf8',

    /**
     * 上传目录和 URL 访问路径
     */
   	'uploadDir' => 'upload',
    'uploadRoot' => UPLOAD_ROOT,

    /**
     * 缩略图的大小、可用扩展名
     */
   // 'thumbWidth' => 166,
//    'thumbHeight' => 166,
      'thumbFileExts' => 'gif,png,jpg,jpeg',

    /**
     * 商品大图片的最大文件尺寸和可用扩展名
     */
    'photoMaxFilesize' => 1024 * 1024,
    'photoFileExts' => 'gif,png,jpg,jpeg',
	'FileExts'	=>'gif,png,jpg,jpeg,txt,doc,rtf,xls,csv,ppt,rar,zip,mp3,wmv,avi,mp4',
    /**
     * 使用默认的控制器 ACT 文件
     *
     * 这样可以避免为每一个控制器都编写 ACT 文件
     */
    'defaultControllerACTFile' => dirname(__FILE__) . DS . 'DefaultACT.php',

    /**
     * 必须设置该选项为 true，才能启用默认的控制器 ACT 文件
     */
    'autoQueryDefaultACTFile' => true,
	
	/*设置没有权限的访问所指向的方法*/
	
	'dispatcherAuthFailedCallback' => 'error_access',
	

	
);
