<?php

/**
* @package ActionDispatch
* 
* ActionDispatch Package Loader
* Version 0.1.0
* Created: August 14th, 2014 by BJA
* Last Modified: N/A
* Description:
*	This include file loads all the required files for the Laurel Framework
*   ActionDispatch
*/

require_once 'Exception/RouteNotFound.php';
require_once 'Exception/UnexpectedValue.php';
require_once 'AbstractSpec.php';
require_once 'RouteGenerator.php';
require_once 'Regex.php';
require_once 'Route.php';
require_once 'RouteCollection.php';
require_once 'RouteFactory.php';
require_once 'Router.php';
require_once 'RouterFactory.php';

$router_factory = new RouterFactory;
$router = $router_factory->newInstance();
