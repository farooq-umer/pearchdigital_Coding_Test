<?php
/*
 *In Order to capture all logs in our logFile, added our logFile path:
 * integration_jira/vendor/lesstif/php-jira-rest-client/src/JiraClient.php ON line 99 Added following constants:
 * LOCAL_LOGS_DIR.'/'.JIRA_LOG_FILENAME,
 */

//require_once dirname(__DIR__).'/workspace/support/config.php'
//require_once dirname(__DIR__).'/apt/config.php';
//define('APP_DIR', '/integration_jira');
//define('APP_ABS_DIR', DOCUMENT_ROOT.APP_DIR);

ini_set("display_errors", 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0); // 1200 sec = 20 minutes
//set_time_limit(0);
//ini_set('memory_limit', '-1');
//ini_set('log_errors', 1); // 0 - off

define('JIRA_DEV', true);

// ======================================================================
//app constant
//define('APT_APP',	true);
	
//DIRECTORIES
	
	//Server root (for for hosting without access to / dirs)
	define('SERVER_ROOT',			'/var/www'); // /var/www/sw/support

	//If the App is not in the main root of the http server please specify the director
	define('APT_DIR',				'/workspace/apt');
	
	//Root directory of the http server
	define('DOCUMENT_ROOT',			SERVER_ROOT . '');

	define('APP_DIR',				'/integration_jira');
	define('APT_ABS_DIR',			DOCUMENT_ROOT.APT_DIR);

	define('APP_ABS_DIR',			DOCUMENT_ROOT.APP_DIR);

	//define directory with classes
	//define('APT_DIR',				SERVER_ROOT . '/apt');
	//define('APT_LOG_DIR',			APT_ABS_DIR . '/log');
	define('CLASS_DIR',				APT_ABS_DIR . '/class');
	//define('CLASS_LOCAL_DIR',		APP_ABS_DIR . '/classes');

	spl_autoload_register('autoloader');
	
	//Class file name prefix
	define('CLASS_FILE_PREFIX',		'class_');
	
	define('DS' , '/');
	
//autoload any necessary classes
function autoloader($class_name) {
	$dirs = array(
		//CLASS_LOCAL_DIR => array(CLASS_LOCAL_DIR),
		CLASS_DIR => array(CLASS_DIR)
	);
	$fileName = CLASS_FILE_PREFIX . strtolower($class_name) . '.php';
	//print_r($fileName);
	while ($storage = array_shift($dirs)) {
		while ($dirName = array_shift($storage)) {
			$dir = dir($dirName);
			while (false !== ($entry = $dir->read())) {
				if ($entry != '.' && $entry != '..') {
					$location = $dirName . DS . $entry;
					if (is_file($location . DS . $fileName)) {
						require_once($location . DS . $fileName);
						return true;
					} elseif (is_dir($location)) {
						$storage[] = $location;
					}
				}				
			}
		}
	}
}
//end

//FUNC::makeDir(APT_LOG_DIR);
//FUNC::makeDir(dirname(__FILE__) . '/log');
ini_set('error_log', dirname(__FILE__) . '/log/' . E::getDailyLogFilename());

// ======================================================================

//ini_set('error_log', dirname(__FILE__) . '/logs/phpLogs.txt');

define('BASE_PATH', __DIR__); // echo BASE_PATH; die;
define('SUCCESS', 'S');
define('ERROR', 'E');

// Local PATHS
define('LOCAL_LOGS_DIR', BASE_PATH .'/log');

//Simply Bot Details
define('SWF_USER_BOT_ID', 146);

//Jira Bot Details
define('JIRA_HOST', 'https://swf.atlassian.net');
//define('JIRA_USER_BOT_KEY', 'jirabot');
define('JIRA_USERPWD', 'swf@swf.com:Yd05bj8sBz6STw6HkbAd843C');

define('JIRA_PORJECT', JIRA_HOST.'/rest/api/2/project');
define('JIRA_CONTENT_TYPE', 'content-type: application/json');
define('JIRA_PROJECT_CODE', 'SUP'); // DEMO, SUP, SPSMWS, SWFTOJ

// Swf Event Constants From - getTaskEventsByDateRange
define( 'SWF_EVENT_TASK_CREATED' , 'TASK_CREATED');
define( 'SWF_EVENT_TASK_NOTE_CREATED' , 'TASK_NOTE_CREATED');
define( 'SWF_EVENT_TASK_STATUS_CHANGED' , 'TASK_STATUS_CHANGED');
define( 'JIRA_TAB_STATUS_UPDATE' , 'JIRA_TAB_STATUS_UPDATE');

//----------------------------------------------------------------------------------------------------------------
$fileName = date('d-m-Y') . '.txt'; // h i s a, hours min sec am/pm
define('JIRA_LOG_FILENAME', 'log_'.$fileName);
define('JIRATOSWF_CT_FILENAME', 'jira_to_swf_'.$fileName);
define('SWFTOJIRA_CT_FILENAME', 'swf_to_jira_'.$fileName);
//----------------------------------------------------------------------------------------------------------------

$http_success = array( 200, 201);

// Set your webservices Instacnce, DEV or LIVE
define('ENVIRONMENT', 'DEV');

if (ENVIRONMENT === 'LIVE') {
    
    define('SWF_INSTANCE', 'localhost/workspace/support');
}
else {
	
    define('SWF_INSTANCE', 'localhost/workspace/support');
}

define('CONTENT_TYPE', 'Content-Type:application/x-www-form-urlencoded');
define('CLIENT_ID', 'client-id:1234');
define('CLIENT_SECRET', 'client-secret:18nbvc3456jjk653406chgjt786727cb00de3adghg939');

require_once (BASE_PATH.'/end_points.php');

require_once (BASE_PATH.'/vendor/autoload.php');
// autoloading the following classes via composer autoloader
// check composer.json file
/*
require_once (BASE_PATH.'/classes/class_WebServices.php');
require_once (BASE_PATH.'/classes/class_SwfToJira.php');
require_once (BASE_PATH.'/classes/class_JiraToSwf.php');
require_once (BASE_PATH.'/classes/class_Sync.php');
*/
require_once (BASE_PATH.'/helper/functions.php');

//use Monolog\Logger as Logger;
//define('LOG_LEVEL', Logger::INFO);
//echo 'Server Time Zone : ' . date_default_timezone_get() . '<br />';

print_r(array('SWF_SUPPORT'=>SWF_INSTANCE,'JIRA_PROJECT_CODE'=>JIRA_PROJECT_CODE,'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'] ));
