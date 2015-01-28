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
	// If debug mode is off ( production ) cache routes
	//
	if(!DEV_MODE)
	{
		$routeCache = CACHE_PATH.'/routes.cache';

		if(file_exists($routeCache))
		{
			$routes = unserialize(file_get_contents($routeCache));
			$router->setRoutes($routes);			
		}
		else
		{
			require_once CONFIG_PATH.'/routes.php';
			$routes = $router->getRoutes();
			file_put_contents($routeCache, serialize($routes));			
		}
	}
	else
	{
		require_once CONFIG_PATH.'/routes.php';
	}
	
	
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
		// Generate the required file name and instantiate the class
		//
		$dir = isset($route->params['directory']) && $route->params['directory'] ? $route->params['directory'].'/' : '';


		//
		// Register a default view ( laurel.default )
		// this view follows the assigned view directory / controllerPrefix / actionName.hh
		//
		$viewName = $controllerName.'/'.$actionName.'.php';
		$view_registry->set('laurel.default',VIEW_PATH.'/'.$dir.$viewName);
		
		
				
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
		$controller->setLayout(isset($route->params['template']) ? $route->params['template'] : DEFAULT_TEMPLATE);
		
	
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
		if(DEV_MODE)
		{
			throw new RouteNotFound('No route matching "'.$_SERVER['REQUEST_URI'].'" was found.');
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
		// UNCAUGHT EXCEPTIONS - INTERNAL SERVER ERROR
		//

		$controller = INTERNAL_CONTROLLER.'Controller';
		require_once CORE_PATH.'/Controllers/'.$controller .'.php';
		
		$controller = new $controller($view_registry,$layout_registry,$helper);
		$controller->setView('laurel.internal');
		$controller->setLayout(INTERNAL_ERROR_TEMPLATE);
		
		$controller->error = $e->getMessage();
		$controller->error_file = $e->getFile();
		$controller->line = $e->getLine();
		$controller->uncaught_exception = true;
		$controller->backtrace = $e;

		$action = INTERNAL_ERROR_METHOD;
		$controller->$action();
		
		echo $controller->__invoke();		
		
}

unset($_SESSION['message']);




