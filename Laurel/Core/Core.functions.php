<?php


//
// Display errors to the screen in a nice template
//
function handle_error($errorNumber, $message, $errfile, $errline,$stacktrace=false) {
	
	$view_factory = new ViewFactory();
	
	$view = $view_factory->newInstance();
	$view_registry = $view->getViewRegistry();
	$layout_registry = $view->getLayoutRegistry();


	$helpers = new HelperFactory();
	$helper = $helpers->newInstance();

    
    $view_registry->set('laurel.error',VIEW_PATH.'/laurel/internal-error.php');
    
	$controller = INTERNAL_CONTROLLER.'Controller';
	require_once CONTROLLER_PATH.'/'.$controller .'.php';
	
	$controller = new $controller($view_registry,$layout_registry,$helper);
	
	$controller->setView('laurel.error');
	$controller->error = $message;
	$controller->last_error = error_get_last();
	$controller->backtrace = debug_backtrace();
	$controller->error_file = $errfile;
	$controller->line = $errline;

	$action = INTERNAL_ERROR_METHOD;
	$controller->$action();
	
	echo $controller->__invoke();	
	

        	
}

//
// Fatal error handler
//
function fatalErrorShutdownHandler()
{

  $last_error = error_get_last();

  if ($last_error['type'] === E_ERROR) {
    handle_error(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
}
	
