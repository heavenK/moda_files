<?php

/**
 * ����Ӧ�ó������� 
*/

return array(
    /**
     * Ӧ�ó������
     */
    //'appTitle' => 'FleaPHP Example SHOP',

    /**
     * ָ��Ĭ�Ͽ�����
     */
    //'defaultController' => 'Login',
	'defaultController' => 'ModaIndex',

	//'defaultController' => 'User',
	//'defaultAction' => 'EnterIndexpage',

    /**
     * ָ��Ҫʹ�õĵ�����
     */
	 
     'dispatcher' => 'FLEA_Dispatcher_Auth',
     /**
     * ָʾ RBAC �����ʲô������ session �б����û�����
     */
	'RBACSessionKey' => 'ktvRBAC',
	 
    /**
     * ָʾӦ�ó����ڲ��������ݼ�ҳ����ʾҪʹ�õı���
     */
    'responseCharset' => 'gbk',

    /**
     * ָʾ���ݿ�Ҫʹ�õı���
     */
    'databaseCharset' => 'gbk',

    /**
     * ���ö�����֧��
     */
    //'multiLangaugeSupport' => true,

    /**
     * ָ�������ļ�����Ŀ¼
     */
  	 // 'languageFilesDir' => realpath(dirname(__FILE__) . '/../Languages'),

    /**
     * ָʾ���õ�����
     */
 	/*'languages' => array(
        '��������' => 'chinese-utf8',
        '��������' => 'chinese-utf8-tw',
    ),*/

    /**
     * ָʾĬ������
     */
    //'defaultLanguage' => 'chinese-utf8',

    /**
     * �ϴ�Ŀ¼�� URL ����·��
     */
   	'uploadDir' => 'upload',
    'uploadRoot' => UPLOAD_ROOT,

    /**
     * ����ͼ�Ĵ�С��������չ��
     */
   // 'thumbWidth' => 166,
//    'thumbHeight' => 166,
      'thumbFileExts' => 'gif,png,jpg,jpeg',

    /**
     * ��Ʒ��ͼƬ������ļ��ߴ�Ϳ�����չ��
     */
    'photoMaxFilesize' => 1024 * 1024,
    'photoFileExts' => 'gif,png,jpg,jpeg',
	'FileExts'	=>'gif,png,jpg,jpeg,txt,doc,rtf,xls,csv,ppt,rar,zip,mp3,wmv,avi,mp4',
    /**
     * ʹ��Ĭ�ϵĿ����� ACT �ļ�
     *
     * �������Ա���Ϊÿһ������������д ACT �ļ�
     */
    'defaultControllerACTFile' => dirname(__FILE__) . DS . 'DefaultACT.php',

    /**
     * �������ø�ѡ��Ϊ true����������Ĭ�ϵĿ����� ACT �ļ�
     */
    'autoQueryDefaultACTFile' => true,
	
	/*����û��Ȩ�޵ķ�����ָ��ķ���*/
	
	'dispatcherAuthFailedCallback' => 'error_access',
	

	
);
