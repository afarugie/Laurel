<?php 

session_start();


date_default_timezone_set('America/Los_Angeles');


try
{
	
	//
	// Load site configurations and Laurel Framework
	//
	require_once '../Config/config.inc.php';
	require_once FRAMEWORK_PATH.'/Framework.php';
	require_once CONFIG_PATH.'/database.php';
	//require_once CONFIG_PATH.'/session.php';

	//
	// Parse the request: index.php/some/request
	// Match it to routes stored in the router.
	// Filter out any trailing commas
	//
	$_SERVER['REQUEST_URI'] = (substr($_SERVER['REQUEST_URI'],-1) == '/' && strlen($_SERVER['REQUEST_URI']) > 1) ? substr($_SERVER['REQUEST_URI'],0,-1) : $_SERVER['REQUEST_URI'];
	$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	$route = $router->match($path, $_SERVER);
	
		
	//
	// If a route matches, fire off the controller
	// Assign the default view / layout unless specified in the route
	// otherwise show the 404 page
	//
	if($route)
	{
		
		
		$controllerName = $route->params['controller'];
		$actionName = $route->params['action'];
			
		//
		// Register a default view ( laurel.default )
		// this view follows the assigned view directory / controllerPrefix / actionName.hh
		//
		$viewName = $controllerName.'/'.$actionName.'.php';
		$view_registry->set('laurel.default',VIEW_PATH.'/'.$viewName);
		
		//
		// Generate the required file name and instantiate the class
		//
		$dir = isset($route->params['directory']) ? $route->params['directory'].'/' : '';
		if(isset($route->params['directory']) && $route->params['directory'] == 'admin')
		{
			$template = ADMIN_TEMPLATE;
		}
		else
		{
			$template = DEFAULT_TEMPLATE;
		}
		
		require_once CONTROLLER_PATH.'/'.$dir.$controllerName.'Controller.php';
		$controllerName .='Controller';
	
		
		$controller = new $controllerName($view_registry,$layout_registry,$helper);
		
		
		
		//
		// register the default view before the action fires off to allow overrides
		//
		$controller->setView('laurel.default');
		
		
		//
		// Unless specified in the routes, use the default template specified in DEFAULT_TEMPLATE
		//
		$controller->setLayout(isset($route->params['template']) ? $route->params['template'] : $template);
		
	
		//
		// Fire off the controller and action: echo out the html response from the HHVM
		//	
		$controller->setData($route->params);
		$controller->$actionName();
		
		//so the user doesn't override these
		$controller->router = $router;
		$controller->controller = $route->params['controller'];
		$controller->action = $route->params['action'];
		
		
		echo $controller->__invoke();
	
	}
	else
	{
		//
		// Throw internal error for testing purposes
		//
		if(DEBUG_MODE)
		{
			throw new RouteNotFound('No Route Matching '.$_SERVER['REQUEST_URI'].' Was Found.');
		}
		
		header("HTTP/1.1 404 Not Found");
		
		//
		//	404 page route not found.
		// 
		$controller = ERROR404_CONTROLLER.'Controller';
		require_once CONTROLLER_PATH.'/'.$controller .'.php';
		
		$controller = new $controller($view_registry,$layout_registry,$helper);
		$controller->setView('laurel.404');
		$controller->setLayout(ERROR404_TEMPLATE);
		
		$action = ERROR404_METHOD;
		$controller->$action();
		
		echo $controller->__invoke();
	
	}
}
catch(Exception $e)
{	
		//
		// INTERNAL SERVER ERROR
		//
		
		$controller = INTERNAL_CONTROLLER.'Controller';
		require_once CONTROLLER_PATH.'/'.$controller .'.php';
		
		$controller = new $controller($view_registry,$layout_registry,$helper);
		$controller->setView('laurel.internal');
		$controller->setLayout(INTERNAL_ERROR_TEMPLATE);
		
		$controller->error = $e;
		
		$action = INTERNAL_ERROR_METHOD;
		$controller->$action();
		
		echo $controller->__invoke();		
		
}

unset($_SESSION['message']);




