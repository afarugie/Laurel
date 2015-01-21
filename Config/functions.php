<?php //strict

//
// puts fatal errors into the stack
//
error_reporting(1);
set_error_handler('handle_error');
register_shutdown_function('fatalErrorShutdownHandler');


//
// Loads an extension from the Extensions directory
// Extension name, Extension Include File
// Extension/xhp/init.php
// load_extension('xhp')
//
function load_extension($name,$extension=".php")
{
	require_once EXTENSION_PATH.'/'.$name.''.$extension;
}



//
// returns an object instance so methods can be executed against it
// $user = with(new User())->update('name','Ben')
//
function with($object)
{
	return $object;
}

function current_user()
{
	return isset($_SESSION['author_id']) ? Author::find_by_id($_SESSION['author_id']) : false;
}

//
// Display errors to the screen in a nice template
// HHVM stops execution once an error occures
//
function handle_error($errorNumber, $message, $errfile, $errline,$stacktrace=false) {
	
	echo "<pre>";
	var_dump($message);
	
	var_dump(debug_backtrace());
	
    switch ($errorNumber) {
        case E_ERROR :
            $errorLevel = 'Error';
            break;

        case E_WARNING :
            $errorLevel = 'Warning';
            break;

        case E_NOTICE :
            $errorLevel = 'Notice';
            break;

        default :
            $errorLevel = 'Fatal';
    }
    
    ob_start();
    	$error = '<br/><b>' . $errorLevel . '</b>: ' . $message . ' in <b>'.$errfile . '</b> on line <b>' . $errline . '</b><br/>';
   		include TEMPLATE_PATH.'/error.php';
    echo ob_get_clean();
        	
}

//
// Fatal error handler
//
function fatalErrorShutdownHandler()
{
	
	
  $last_error = error_get_last();
  
//  var_dump(debug_backtrace());
  //var_dump($last_error);
  
  if ($last_error['type'] === E_ERROR) {
    // fatal error
    handle_error(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
}

