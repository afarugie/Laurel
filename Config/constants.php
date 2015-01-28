<?php //strict

//
// Directory Paths - Framework Specific
//

define('BASE_PATH',dirname(dirname(__FILE__)));



define('CONFIG_PATH',BASE_PATH.'/Config');
define('EXTENSION_PATH',BASE_PATH.'/Extensions');
define('APPLICATION_PATH',BASE_PATH.'/Application');
define('LOG_PATH',BASE_PATH.'/Log');
define('FRAMEWORK_PATH',BASE_PATH.'/Laurel');
define('CORE_PATH',FRAMEWORK_PATH.'/Core');

//
// Application related constants
//
define('DEV_MODE',true);

define('ENVIRONMENT','staging');
define('CONTROLLER_PATH',APPLICATION_PATH.'/Controllers');
define('VIEW_PATH',APPLICATION_PATH.'/Views');
define('TEMPLATE_PATH',APPLICATION_PATH.'/Views/Templates');
define('HELPER_PATH',APPLICATION_PATH.'/Helpers');
define('MODEL_PATH',APPLICATION_PATH.'/Models');
define('CACHE_PATH',APPLICATION_PATH.'/Cache');

//
// Fatal errors, backtrace
//
define('INTERNAL_CONTROLLER','laurel');
define('INTERNAL_ERROR_METHOD','internalError');
define('INTERNAL_ERROR_TEMPLATE','master');

//
// Styles / behavior
//
define('CSS_PATH','/Assets/css/');
define('IMAGES_PATH','/Assets/images/');
define('JAVASCRIPT_PATH','/Assets/javascript/');


//
// Site Constants
//
define('DEFAULT_TEMPLATE','master');

define('ERROR404_CONTROLLER','index');
define('ERROR404_METHOD','error404');
define('ERROR404_TEMPLATE','master');


//
// Overload any constants for customization here..
// I'll probably add a if file_exists(), then overload in 
// REQUEST_URI['http_host'] / Assets/ w.e file was requested
//



//
// PHP Include Path 
//
//set_include_path('/var/www');
