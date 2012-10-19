<?php
define('APP_DIR',"../APP");
define('UPLOAD_DIR', realpath(APP_DIR . '/upload'));
define('UPLOAD_ROOT', 'upload');
define('TPL_DIR', dirname(__FILE__) . '/templates');
//define('DEPLOY_MODE', true);
//ini_set("display_errors","off");
//ini_set("display_errors","on");

define('DBIP', '192.168.1.9');
define('DBUSER', 'user1');
define('DBPW', '123');
define('DBNAME', 'bbsup');


require('../../FLEA/FLEA.php');
FLEA::setAppInf('displayErrors',false);
//set_app_inf('dispatcher', 'FLEA_Dispatcher_Auth');
FLEA::loadAppInf(APP_DIR . '/Config/DSN.php');
FLEA::loadAppInf(APP_DIR . '/Config/APP_INF.php');

FLEA::import(APP_DIR );
FLEA::runMVC();
function error_access(){
	echo "路径错误，或者非法访问！";
	exit();
}
//echo json_encode(array("user_name"=>"caonima","user_password"=>"caoniba"));
?>